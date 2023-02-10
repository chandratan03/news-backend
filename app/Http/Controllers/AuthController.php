<?php

namespace App\Http\Controllers;

use App\Constants\HttpResponse;
use App\Helper\MyHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => bcrypt($request["password"]),
        ]);

        $token = $user->createToken("apiToken")->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

        return MyHelper::customResponse(
            $res,
            "",
            HttpResponse::HTTP_CREATED,
        );
    }


    public function login(LoginRequest $request)
    {

        $user = User::where("email", $request["email"])->first();

        if (!$user || Hash::check($user->password, $request["password"])) {
            return response(
                [
                    "message" => "incorrect email or password",
                ],
                401
            );
        }

        $token = $user->createToken("apiToken")->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

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
}
