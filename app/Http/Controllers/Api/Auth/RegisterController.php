<?php


namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
//      Проверка существует ли пользователь с такой почтой
        $foundUser = User::where('email', $request['email'])->count();
        if ($foundUser > 0) {
            return response()->json([
                'errors' => [
                    'email' => [
                        'Пользователь с такой почтой уже существует'
                    ]
                ]
            ], 409);
        }
//      Создаем пользователя и проходим валидацию
        $user = User::create([
            'login' => $request['login'],
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'github' => $request['github'],
            'city' => $request['city'],
            'is_finished' => '1',
            'phone' => $request['phone'],
            'birthday' => $request['birthday'],
            'password' => $request['password'] = bcrypt($request->password),

        ]);
//      Access token
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['token' => $accessToken, 'user' => $user,]);
    }
}
