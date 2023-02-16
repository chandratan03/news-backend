<?php

namespace App\Services\Implementation;

use App\Repositories\UserRepositoryInterface;
use App\Services\IUserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService implements IUserService
{

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($data)
    {
        $user = $this->userRepository->create($data);

        $token = $user->createToken("apiToken")->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

        return $res;
    }

    public function login($email, $password)
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken("apiToken")->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

        return $res;
    }

    public function update($data)
    {
        $payload = [];
        $user = auth()->user();
        foreach ($data as $key => $value) {

            if ($key === "image" && !empty($value)) {
                $prefixImage = "images";
                $imageName = Str::uuid()->toString() . "." .  $value->getClientOriginalExtension();
                $value->storeAs("public/" . $prefixImage, $imageName);
                $payload["image_url"] = url("/") . "/" . "storage/" . $prefixImage . "/" . $imageName;

            } else if ($value !== null) {
                $payload[$key] = $value;
            }
        }
        $result = $this->userRepository->update($user->id, $payload);
        if ($result) {
            return $this->userRepository->findById($user->id);
        }
        return "failed";
    }
}
