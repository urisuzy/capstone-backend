<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponser;
    public function me()
    {
        try {
            $userId = Auth::id();
            $user = User::with('interests')->where('id', $userId)->first();

            return $this->successResponse(new UserResource($user));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateImageProfile(Request $request)
    {
        try {
            $this->validate($request, [
                'image' => 'image'
            ]);

            $user = User::find(Auth::id());

            // Upload Image
            $path = $request->file('image')->store('uploads');

            $user->profile = $path;
            $user->save();

            $response = [
                'image' => config('app.url') . "/{$user->profile}"
            ];

            return $this->successResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateUserInfo(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => '',
                'password' => ''
            ]);

            $user = User::find(Auth::id());
            if (!empty($request->username))
                $user->username = $request->username;

            if (!empty($request->password)) {
                $pass = bcrypt($request->password);
                $user->password = $pass;
            }
            $user->save();

            return $this->successResponse(new UserResource($user));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
