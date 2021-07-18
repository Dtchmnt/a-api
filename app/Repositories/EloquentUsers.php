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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
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
        return response()->json('Что то пошло не так', 400);
    }

    /**
     * @param Request $req
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getById(Request $request, int $id)
    {
        //Делаем запрос юзера
        $user = $request->user();
//Ищем юзера по id
        $worker = $this->model->findOrFail($id);
        //Если я админ то получаю все юзеров по ид
        if ($user->hasRole('admin')) {
            //выводим юзера
            return response()->json([
                'worker' => [
                    $worker
                ]
            ], 200);
        }
        //Если я воркер то получаю все юзеров по ид своего отдела
        if ($user->hasRole('user', 'worker')
            && $user->position == null
            || $worker->position == null
            || $worker->position->department->id
            != $user->position->department->id) {
            //выводим доступ запрещен
            return response()->json([
                'errors' => [
                    'message' => [
                        'Доступ запрещен'
                    ]
                ]
            ], 400);
        }
//статус 200
        return response()->json([
            'worker' => [
                $worker
            ]
        ], 200);
    }

    public function getUserCard()
    {
        
    }
}
