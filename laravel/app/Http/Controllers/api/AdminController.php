<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin; // Import the Admin model
use App\Http\Requests\UpdateAdminRequest; // Update request for Admin
use App\Http\Requests\UpdateAdminVcardPasswordRequest; // Update password request for Admin
use Illuminate\Http\Request;
use App\Http\Resources\AdminResource; // Resource for Admin
use App\Http\Requests\StoreAdminRequest; // Store request for Admin

class AdminController extends Controller
{
    public function index()
    {
        return AdminResource::collection(Admin::all());
    }

    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    // REGISTRATION - CREATES A NEW ADMIN
    public function store(StoreAdminRequest $request)
    {
        $dataToSave = $request->validated();

        $admin = new Admin();
        $admin->name = $dataToSave['name'];
        $admin->email = $dataToSave['email'];
        $admin->password = bcrypt($dataToSave['password']);

        $admin->save();
        return new AdminResource($admin);
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $dataToSave = $request->validated();

        $admin->fill($dataToSave);

        $admin->save();
        return new AdminResource($admin);
    }

    public function update_password(UpdateAdminVcardPasswordRequest $request, Admin $admin)
    {
        // Proceed with updating the password
        $admin->password = bcrypt($request->validated()['new_password']);
        $admin->save();
        return new AdminResource($admin);
    }

    public function show_me(Request $request)
    {
        $user = $request->user();
        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //dd($user);
        // Retrieve the Vcard based on the authenticated user's phone_number
        $admin = Admin::where('id', $user->id)->first();

        // Check if the Vcard is found and authorized
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        // Return Vcard information using a resource class (assuming you have a VcardResource class)
        return new AdminResource($admin);
    }
}
