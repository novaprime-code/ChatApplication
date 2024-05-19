<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{

    public function index();

    public function register($request);

    public function update($request, $id);

    public function authUser($id);
}
