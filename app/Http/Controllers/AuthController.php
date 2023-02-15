<?php

namespace App\Http\Controllers;

use App\Constants\HttpResponse;
use App\Helper\MyHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function register(RegisterRequest $request)
    {
        $res = $this->userService->create([
            "first_name" => $request["first_name"],
            "last_name" => $request["last_name"],
            "email" => $request["email"],
            "password" => bcrypt($request["password"]),
        ]);

        return MyHelper::customResponse(
            $res,
            "",
            HttpResponse::HTTP_CREATED,
        );
    }


    public function login(LoginRequest $request)
    {
        $res = $this->userService->login($request["email"], $request["password"]);
        if (!$res) {
            return MyHelper::customResponse(
                [],
                "Incorrect email or password",
                HttpResponse::HTTP_FORBIDDEN
            );
        }

        return MyHelper::customResponse(
            $res,
            "",
            HttpResponse::HTTP_CREATED
        );
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        $message = "successfully logout";
        return MyHelper::customResponse([], $message);
    }

    public function update(UpdateRequest $request)
    {
        $data["first_name"] = $request["first_name"];
        $data["last_name"] = $request["last_name"];
        $data["password"] = empty($request["password"]) ? null : bcrypt($request["password"]);
        $data["confirmPassword"] = $request["confirm_password"];
        $data["image"] = $request->file('image');

        $result = $this->userService->update($data);
        return MyHelper::customResponse($result);
    }
}
