<?php

namespace App\Http\Controllers;

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
            'task_id' => 'required|numeric|min:1|nullable'
        ]);

        if (!isset($inputs['task_id']) || empty($inputs['task_id'])) {
            $task = $this->tasks->createTask($request->user());
        } else {
            $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        }

        $this->tasks->updateStatusFor($task->id);

        return view('task', [
            'task' => $task->toArray()
        ]);
    }

    /**
     * Set next status for task
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function nextStatus(Request $request) {
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
        return redirect()->action('TaskController@index', ['task_id' => $task->task_id]);
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
    public function delete(Request $request) {
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
     * change name for task
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function changeName(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'required|numeric|min:1',
            'name' => 'required|string|min:1|max:255',
        ]);

        $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        $this->tasks->updateTask($task, $inputs['name']);

        if ($request->ajax()) {
            return response()->json(['status' => 'saved']);
        }
        return redirect()->action('TaskController@index', ['task_id' => $task->task_id]);
    }

    /**
     * Change description for task
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeDescription(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
        ]);

        $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        $this->tasks->updateTask($task, null, $inputs['description']);

        if ($request->ajax()) {
            return response()->json(['status' => 'saved']);
        }
        return redirect()->action('TaskController@index', ['task_id' => $task->task_id]);
    }

    /**
     * Change type for task
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeType(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'required|numeric|min:1',
            'type' => ['required', Rule::in([1, 2, 3, 4])],
        ]);

        $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        $this->tasks->updateTask($task, null, null, null, null, $inputs['name']);

        if ($request->ajax()) {
            return response()->json(['status' => 'saved']);
        }
        return redirect()->action('TaskController@index', ['task_id' => $task->task_id]);
    }

    /**
     * Change dates for task
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeDates(Request $request)
    {
        $inputs = $request->validate([
            'task_id' => 'required|numeric|min:1',
            'begin_in' => 'required|date|required_with:finish_in',
            'finish_in' => 'required|date|required_with:begin_in|greater_date:begin_in',
        ]);

        $task = $this->tasks->getOneFor($request->user(), $inputs['task_id']);
        $this->tasks->updateTask($task, null, null, $request['begin_in'], $request['finish_in']);

        if ($request->ajax()) {
            return response()->json(['status' => 'saved']);
        }
        return redirect()->action('TaskController@index', ['task_id' => $task->task_id]);
    }
}
