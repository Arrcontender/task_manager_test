<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Отдает объект проекта
     */
    public function show($id)
    {
        return new ProjectResource(Project::findOrFail($id));
    }

    /**
     * Отдает задачи проекта (с фильтром по пользователю)
     */
    public function showTasks($id, Request $request)
    {
        $userId = $request->get('user_id');
        $tasks = new ProjectResource(Project::findOrFail($id)->first());
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
     * Создает новый проект
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());
        return $project;
    }

    /**
     * Обновляет проект
     */
    public function update(ProjectRequest $request, int $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->validated());
        $project->save();
        return response()->json($project);
    }

    /**
     * Удаляет задачу
     */
    public function destroy(int $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        $returnData = array(
            'status' => 'success'
        );
        return response()->json($returnData, 200);
    }
}
