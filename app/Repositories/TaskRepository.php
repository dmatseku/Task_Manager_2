<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Task;

class TaskRepository extends Model
{
    /**
     *
     * Update statuses of tasks by them begin_in and finish_in columns
     *
     */
    public function updateStatuses()
    {
        $tasks = Task::where('status', '<', 3)->get();
        $date_now = date('Y-m-d');

        foreach ($tasks as $task) {
            if ($task->finish_in <= $date_now) {
                $task->status = 3;
            } elseif ($task->status === 1 && $task->begin_in <= $date_now) {
                $task->status = 2;
            }
            $task->save();
        }
    }

    /**
     *
     * Update status of one task by them begin_in and finish_in columns
     *
     * @param int $task_id
     *
     */
    public function updateStatusFor(int $task_id)
    {
        $task = Task::find($task_id);
        $date_now = date('Y-m-d');

        if ($task->finish_in <= $date_now) {
            $task->status = 3;
        } elseif ($task->status === 1 && $task->begin_in <= $date_now) {
            $task->status = 2;
        }
        $task->save();
    }

    /**
     *
     * get all tasks for user
     *
     * @param User $user
     * @param string $pattern
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     */
    public function getFor(User $user, string $pattern)
    {
        return ($user->tasks()->where('name', 'like', '%'.trim($pattern).'%')->get());
    }

    /**
     *
     * get one task for user
     *
     * @param User $user
     * @param int $task_id
     *
     * @return Task
     *
     */
    public function getOneFor(User $user, int $task_id)
    {
        $task = $user->tasks()->where('id', '=', $task_id)->limit(1)->get();
        if (!count($task)) {
            abort(403);
        }
        return $task->first();
    }

    /**
     *
     * Create new task
     *
     * @param User $user
     *
     * @return Task
     *
     */
    public function createTask(User $user)
    {
        $task = new Task;

        $task->user_id = $user->id;
        $task->begin_in = date('Y-m-d');
        $task->finish_in = date('Y-m-d', strtotime('tomorrow'));
        $task->save();

        return $task;
    }

    /**
     *
     * Update all not null fields.
     * Update status if task is new or has updated dates.
     *
     * @param Task $task
     * @param string|null $name
     * @param string|null $description
     * @param string|null $begin_in
     * @param string|null $finish_in
     * @param int|null $type
     * @param bool $needUpdateStatus
     *
     * @return void
     */
    public function updateTask(Task $task, string $name = null, string $description = null, string $begin_in = null,
                           string $finish_in = null, int $type = null)
    {
        $date_now = date('Y-m-d');
        $needUpdateStatus = false;
        $name = trim($name);
        $description = trim($description);

        if ($name) {
            $task->name = $name;
        }
        if ($description) {
            $task->description = $description;
        }
        if ($begin_in) {
            $task->begin_in = $begin_in;
            $needUpdateStatus = true;
        }
        if ($finish_in) {
            $task->finish_in = $finish_in;
            $needUpdateStatus = true;
        }
        if ($type) {
            $task->type = $type;
        }

        if ($needUpdateStatus) {
            if ($task->finish_in <= $date_now) {
                $task->status = 3;
            } elseif ($task->begin_in <= $date_now) {
                $task->status = 2;
            } else {
                $task->status = 1;
            }
        }

        $task->save();
    }

    /**
     *
     * begin earlier than begin_in date or finish earlier than finish_in date
     *
     * @param Task $task
     *
     * @return void
     *
     */
    public function setNextStatus(Task $task)
    {
        switch ($task->status) {
            case 1:
                $task->status = 2;
                break;
            case 2:
                $task->status = 3;
                break;
        }
        $task->save();
    }

    /**
     *
     * delete existing task
     *
     * @param Task $task
     *
     * @return bool|void|null
     *
     * @throws Exception
     */
    public function deleteTask(Task $task)
    {
        if ($task) {
            $task->delete();
            return true;
        }
        return false;
    }
}
