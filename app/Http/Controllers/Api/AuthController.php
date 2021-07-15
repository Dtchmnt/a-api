<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\RestoreConfirmRequest;
use App\Http\Requests\Api\SendRequest;
use App\Models\Restore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        return response()->json(['token' => $accessToken, 'user' => $user,]);
    }

    /**
     * @param SendRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(SendRequest $request)
    {
        //Реквест только почту
        $email = $request->only('email');
        $user = User::where('email', '=', $email['email'])->first();
        //Если юзера с такой почтой нет возвращаем ошибку
        if (!$user) {
            return response()->json([
                'errors' => [
                    'email' => [
                        'Пользователь с такой почтой не существует'
                    ]
                ]
            ], 404);
        }
        //Генерируем токен и делаем запрос
        $token = Str::random(20);
        // сохраняем в базу запрос на восстановление
        Restore::insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        {
            //Возвращаем json со статусом 201
            return response()->json([
                        'password' => [
                            'Запрос на восстановление пароля был отправлен'
                        ]
            ], 201);
        }
    }

    /**
     * @param RestoreConfirmRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(RestoreConfirmRequest $request)
    {
        //Ищем юзера по токену
        $token = Restore::where('token', $request->token)->first();
        //Если не находим выводим ошибку и статус 404
        if (!$token) {
            return response()->json([
                'errors' => [
                    'Пользователь с таким токеном не найден'
                ],
            ], 404);
        }
        //Ищем юзера по почте
        $user = User::where('email', $token->email)->first();
        //Если вдруг как то почта не совпадает возращаем ошибку
        if (!$user){
            return response()->json([
               'errors' => [
                   'Пользователь сменил почту неоходимо создать новый запрос'
               ]
            ], 404);
        }
        //Если прошло все успешно обновляем пароль и удаляем все созданные токены юзера и возвращаем 201
        $user->password = bcrypt($request->password);
        $user->update();
        Restore::where('email', $user->email)->delete();

        return response()->json([
            'password' => [
                'Пользователь успешно сменил пароль'
            ]
        ], 201);
    }
}
