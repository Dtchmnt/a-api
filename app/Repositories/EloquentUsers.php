<?php

namespace App\Repositories;

use App\Http\Requests\Api\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
