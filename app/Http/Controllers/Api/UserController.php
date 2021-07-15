<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    //показываем всем пользователей
    public function show()
    {
        $user = Auth::user();
        return response()->json(['Информация о пользователе' => $user]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    //обновляем данные юзера
    public function update(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $user->name = $input['show_name'];
        $user->about = $input['about'];
        $user->github = $input['github'];
        $user->city = $input['city'];
        $user->is_finished = true;
        $user->telegram = $input['telegram'];
        $user->phone = $input['phone'];
        $user->birthday = $input['birthday'];
        $user->save();

        return response()->json(['Информация о пользователе' => $user]);
    }
}
