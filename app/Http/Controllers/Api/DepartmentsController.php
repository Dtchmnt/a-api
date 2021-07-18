<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Repositories\EloquentUsers;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function getDepartments(EloquentUsers $users, Request $request)
    {
        return response()->json($users->getDepartments($request));
    }
}
