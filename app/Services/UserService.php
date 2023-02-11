<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($data){
        $user = $this->userRepository->create($data);

        $token = $user->createToken("apiToken")->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

        return $res;
    }

    public function login($email, $password){
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !Hash::check($user->password, $password)) {
            return null;
        }

        $token = $user->createToken("apiToken")->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

        return $res;

    }

}
