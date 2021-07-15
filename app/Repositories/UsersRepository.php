<?php

namespace App\Repositories;


use App\Http\Requests\Api\UpdateRequest;

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
}
