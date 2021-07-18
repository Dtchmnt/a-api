<?php

namespace App\Repositories;


use App\Http\Requests\Api\UpdateRequest;
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
}
