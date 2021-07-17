<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function getDepartments(Request $request) {
        $user = $request->user();
        if ($user->hasRole('user')) {
            $departments = Department::all();
            return response(['departments' => $departments],200);
        }
        if ($user->hasRole('worker')) {
            return response()->json('worker');
        }
        if ($user->hasRole('admin')) {
            return response()->json('admin');
        }
    }
}
