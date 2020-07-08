<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * TaskRepository instance
     *
     * @var TaskRepository
     */
    protected $tasks;

    /**
     * Create a new controller instance.
     *
     * @param TaskRepository $tasks
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');
        $this->middleware('verified');

        $this->tasks = $tasks;
    }

    /**
     *
     * Show task details. Create new task if input is empty
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function index(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'numeric|min:1|nullable'
        ]);

        if (!$request->has('task_id') || empty($inputs['task_id'])) {
            $task = $this->tasks->createTask($request->user());
        } else {
            $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        }

        $this->tasks->updateStatusFor($task);

        return view('tasks.task', [
            'task' => $task->toArray(),
            'types' => Task::getTypesArray()
        ]);
    }

    /**
     * Set next status for task
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function nextStatus(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'required|numeric|min:1',
        ]);

        $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        $this->tasks->setNextStatus($task);

        if ($request->ajax()) {
            return response()->json([
                'status' => $task->status,
                'begin_in' => $task->begin_in,
                'finish_in' => $task->finish_in,
            ]);
        }
        return redirect()->route('task', ['task_id' => $task->task_id]);
    }

    /**
     * Deleting task and redirecting to main page
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'required|numeric|min:1',
        ]);

        $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        $this->tasks->deleteTask($task);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect('/home');
    }

    /**
     * Change some of properties of task
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function changeTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|numeric|min:1',
            'name' => 'min:1|max:255|string',
            'description' => 'nullable|string|min:1|max:1020',
            'type' => [Rule::in([1, 2, 3, 4])],
            'begin_in' => 'date|required_with:finish_in',
            'finish_in' => 'date|required_with:begin_in|greater_date:begin_in',
        ], [
            'name.required' => 'Name can\'t be empty.',
            'name.string' => 'Name can\'t be empty.',
            'finish_date.greater_date' => 'Finish date can\'t be earlier than begin date.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ], 400);
            }
            return redirect()->back()
                ->withErrors($validator);
        }

        $task = $this->tasks->getOneFor($request->user(), $request->input('task_id'));

        // Input description can be empty as null, but for the repository, this means nothing to change.
        // So we need to detect and change description
        $description = $request->input('description', null);
        if ($description === null && $request->has('description')) {
            $description = '';
        }

        $this->tasks->updateTask($task,
                                 $request->input('name', null),
                                 $description,
                                 $request->input('begin_in', null),
                                 $request->input('finish_in', null),
                                 $request->input('type', null));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'status' => $task->status]);
        }
        return redirect()->action('TaskController@index', ['task_id' => $task->task_id]);
    }
}
