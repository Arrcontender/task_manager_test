<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Отдает объект проекта
     */
    public function show($id)
    {
        return new ProjectResource(Project::find($id));
    }

    /**
     * Отдает задачи проекта (с фильтром по пользователю)
     */
    public function showTasks($id, Request $request)
    {
        $userId = $request->get('user_id');
        $tasks = new ProjectResource(Project::find($id)->first());
        $tasks = $tasks['tasks'];
        $res = $tasks;
        if ($userId) {
            $res = [];
            foreach ($tasks as $task) {
                if ($task['user_id'] == $userId) {
                    $res[] = $task;
                }
            }
        }
        return $res;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
