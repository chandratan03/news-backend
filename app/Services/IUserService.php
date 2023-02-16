<?php

namespace App\Services;


interface IUserService
{
    public function create($data);
    public function login($email, $password);
    public function update($data);
}
