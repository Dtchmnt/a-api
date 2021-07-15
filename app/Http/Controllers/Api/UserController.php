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
        return response()->json(['success' => $user]);
    }
}
