<?php

namespace App\Repositories;

use App\Http\Requests\Api\UpdateRequest;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentUsers implements UsersRepository
{
    protected $users;

    public function __construct(User $users)
    {
        $this->model = $users;
    }

    /**
     * {@inheritdoc}
     */

    public function getAuthUser()
    {
        //Возвращаем авторизованого юзера
        return Auth::user();
    }

    /**
     * @param UpdateRequest $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function updateAuthUser(UpdateRequest $request)
    {
        //Авторизованый юзер
        $user = Auth::user();
        //Валидируем поля
        $input = $request->all();

        $user->name = $input['show_name'];
        $user->image = $input['image'];
        $user->about = $input['about'];
        $user->github = $input['github'];
        $user->city = $input['city'];
        $user->is_finished = true;
        $user->telegram = $input['telegram'];
        $user->phone = $input['phone'];
        $user->birthday = $input['birthday'];
        //Сохраняем юзера
        $user->save();

        return $user;
    }

    public function getDepartments(Request $request)
    {
        //Делаем запрос
        $user = $request->user();
        //Если у юзера роль user
        if ($user->hasRole('user')) {
            //Мы возращаем все отделы ему со статусом 200
            $departments = Department::all();
            return response(['departments' => $departments], 200);
        }
        //Если у юзера роль worker
        if ($user->hasRole('worker')) {
            //Мы выводим только те поля которые мы нужны со связью
            $user = $user->only([
                $user->id,
                $user->name,
                $user->position->department->name
            ]);
            //статус 200
            return response()->json(['list of position and department' => $user], 200);
        }
        //Если у юзера роль admin
        if ($user->hasRole('admin')) {
            //мы выводим все позиции и отделы которые есть
            $position = Position::with('department')->get([
                'name',
                'department_id'
            ]);
            //статус 200
            return response(['list of positions and departments' => $position], 200);
        }
        //Если вдруг что то пошло не по плану)
        return response()->json('Что то пошло не так',400);
    }
}
