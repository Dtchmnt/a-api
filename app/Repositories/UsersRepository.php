<?php

namespace App\Repositories;


use App\Http\Requests\Api\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;


interface UsersRepository
{
    /**
     * @return mixed
     */
    public function getAuthUser();

    /**
     * @param UpdateRequest $request
     * @return mixed
     */
    public function updateAuthUser(UpdateRequest $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDepartments(Request $request);

    /**
     * @param Request $req
     * @param int $id
     * @return User
     */
    public function getById(Request $req, int $id);

    public function getUserCard();
}
