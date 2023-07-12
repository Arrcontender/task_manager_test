<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;


class TaskController extends Controller
{
    /**
     * Создает новую задачу
     */
    public function store(int $id, TaskRequest $request)
    {
        $arr = $request->validated();
        $arr += ['project_id' => $id];
        $task = Task::create($arr);
        return $task;
    }

    /**
     * Обновляет задачу
     */
    public function update(int $id, int $task_id, TaskRequest $request)
    {
        $task = Task::findOrFail($task_id);
        if ($task->project_id == $id) {
            $arr = $request->validated();
            $arr += ['project_id' => $id];
            $task->update($arr);
            $task->save();
            return response()->json($task);
        } else {
            $returnData = array(
                'status' => 'error',
                'message' => 'Task with this project id does not exist.'
            );
            return response()->json($returnData, 400);
        }
    }

    /**
     * Удаляет задачу
     */
    public function destroy(int $id, int $task_id,)
    {
        $task = Task::findOrFail($task_id);
        if ($task->project_id == $id) {
            $task->delete();
            $returnData = array(
                'status' => 'success'
            );
            return response()->json($returnData, 200);
        } else {
            $returnData = array(
                'status' => 'error',
                'message' => 'Task with this project id does not exist.'
            );
            return response()->json($returnData, 400);
        }
    }
}
