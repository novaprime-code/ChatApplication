<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function register($request);

    public function update($request, $id);
}
