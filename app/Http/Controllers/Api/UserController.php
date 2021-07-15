<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateRequest;
use App\Repositories\EloquentUsers;

class UserController extends Controller
{
    /**
     * @param EloquentUsers $users
     * @return \Illuminate\Http\JsonResponse
     */
    //показываем всем пользователей
    public function show(EloquentUsers $users)
    {
        return response()->json($users->getAuthUser());
    }

    /**
     * @param EloquentUsers $user
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    //Показываем обновленые данные юзера
    public function update(EloquentUsers $user, UpdateRequest $request)
    {
        return response()->json($user->updateAuthUser($request));
    }
}
