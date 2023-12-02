<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserVcardPasswordRequest;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\Base64Services;


class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    // REGISTRATION - CREATES A NEW USER
    public function store(StoreUserRequest $request)
    {
        $dataToSave = $request->validated();

        $user = new User();
        $user->name = $dataToSave['name'];
        $user->email = $dataToSave['email'];
        $user->password = bcrypt($dataToSave['password']);

        $user->save();
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $dataToSave = $request->validated();

        $user->fill($dataToSave);

        $user->save();
        return new UserResource($user);
    }

    public function update_password(UpdateUserVcardPasswordRequest $request, User $user)
    {
        // Check if the current user is authenticated
        // if (!Auth::check() || !Auth::user()->is($user)) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        // Proceed with updating the password
        $user->password = bcrypt($request->validated()['new_password']);
        $user->save();
        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return new UserResource($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
