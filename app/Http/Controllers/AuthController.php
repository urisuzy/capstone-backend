<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        $data = $request->toArray();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        if ($user) {
            return $this->successResponse($user);
        } else {
            return $this->errorResponse('Error when create, please try again');
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|exists:users',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->toArray())) {
            $user = Auth::user();
            $createToken = $user->createToken('capstone-unique-token666');
            $accessToken = $createToken->plainTextToken;

            return $this->successResponse([
                'email' => $user->email,
                'access_token' => $accessToken
            ]);
        } else {
            return $this->errorResponse('Email or Password is incorrent', 401);
        }
    }

    public function logout()
    {
        Auth::logout();

        return $this->successResponse(true);
    }
}
