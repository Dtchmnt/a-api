<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
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
//        Метод проверки потверждена ли почта
//        if (!Auth::user()->email_verified_at) {
//            return response()->json([
//                'errors' => [
//                    'message' => [
//                        'Не потверждена почта'
//                    ]
//                ]
//            ], 406);
//        }

//      AccessToken
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
