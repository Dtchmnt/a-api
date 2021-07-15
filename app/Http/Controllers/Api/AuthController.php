<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function login(LoginRequest $loginRequest)
    {
//      Проверка введеных данных
        $email = $loginRequest['email'];
        $password = $loginRequest['password'];
//      Если пользователь вводит неправильные данные выводим ошибку
        if (!auth()->attempt(['email' => $email, 'password' => $password])) {
            return response()->json([
                'errors' => [
                    'message' => [
                        'Ошибка в заполнении данных'
                    ]
                ]
            ], 408);
        }
//      AccessToken
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
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
            'is_finished' => true,
            'phone' => $request['phone'],
            'birthday' => $request['birthday'],
            'password' => $request['password'] = bcrypt($request->password),

        ]);
//      Access token
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['token' => $accessToken, 'user' => $user,]);
    }
}
