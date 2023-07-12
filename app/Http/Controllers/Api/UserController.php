<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Отдает объект пользователя
     */
    public function show(int $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Отдает проекты пользователя (с фильтром)
     */
    public function showProjects(int $id, Request $request)
    {
        $search = $request->get('search');
        $projects = new UserResource(User::findOrFail($id)->first());
        $projects = $projects['projects'];
        $res = $projects;
        if ($search) {
            $res = [];
            foreach ($projects as $project) {
                if (stripos($project['name'], $search) !== false) {
                    $res[] = $project;
                }
            }
        }
        return $res;
    }
}
