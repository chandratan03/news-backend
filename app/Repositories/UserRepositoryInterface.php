<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByEmail(string $email): ?User;
}
