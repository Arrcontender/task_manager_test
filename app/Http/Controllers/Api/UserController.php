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
    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    /**
     * Отдает проекты пользователя (с фильтром)
     */
    public function showProjects($id, Request $request)
    {
        $search = $request->get('search');
        $projects = new UserResource(User::find($id)->first());
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
