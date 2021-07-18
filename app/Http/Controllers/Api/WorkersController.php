<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\EloquentUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class WorkersController extends Controller
{
    public function getById(EloquentUsers $usersEloquent, $id, Request $req)
    {
        try {
            return response()->json($usersEloquent->getById($req, $id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
