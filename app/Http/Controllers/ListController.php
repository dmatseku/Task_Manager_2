<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class ListController extends Controller
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
     * show list of tasks or return json response
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     *
     */
    public function index(Request $request)
    {
        $inputs = $request->validate([
            'search' => 'string|max:255'
        ]);

        $this->tasks->updateStatuses();

        $result = $this->tasks->getFor($request->user(), ($inputs['search'] ?? ''))->toArray();

        if ($request->ajax()) {
            return response()->json($result);
        }

        return view('tasks.list', [
            "taskList" => $result
        ]);
    }
}
