<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'search' => 'string|max:255|nullable'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false], 400);
            }
            return redirect('/home')
                ->withErrors($validator)
                ->withInput();
        }

        $this->tasks->updateStatuses();
        $search = $request->input('search', '');
        $result = $this->tasks->getFor($request->user(), $search ?? '')->toArray();

        return view('tasks.'.($request->ajax() ? 'part_list' : 'list'), [
            "taskList" => $result
        ]);
    }
}
