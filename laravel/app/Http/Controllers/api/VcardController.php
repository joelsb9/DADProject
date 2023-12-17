<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Vcard;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\DefaultCategory;
use App\Services\Base64Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VcardResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVcardRequest;
use App\Http\Requests\UpdateVcardRequest;
use App\Http\Requests\UpdateAdminVcardPasswordRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VcardController extends Controller
{
    private function storeBase64AsFile(VCard $vcard, string $base64String)
    {
        $targetDir = storage_path('app/public/fotos');
        $newfilename = $vcard->phone_number . "_" . rand(1000,9999);
        $base64Service = new Base64Services();
        return $base64Service->saveFile($base64String, $targetDir, $newfilename);
    }

    public function index()
    {
        return VcardResource::collection(Vcard::withTrashed()->paginate(10));
    }


    public function show(Vcard $vcard)
    {
        return new VcardResource($vcard);
    }

    public function store(StoreVcardRequest $request)
    {
        $dataToSave = $request->validated();

        $vcard = new Vcard();
        $vcard->phone_number = $dataToSave['phone_number'];
        $vcard->name = $dataToSave['name'];
        $vcard->email = $dataToSave['email'];
        $vcard->password = bcrypt($dataToSave['password']);
        $vcard->confirmation_code = bcrypt($dataToSave['confirmation_code']);

        // Create a new photo file from base64 content
        $base64ImagePhoto = array_key_exists("base64ImagePhoto", $dataToSave) ?
            $dataToSave["base64ImagePhoto"] : ($dataToSave["base64ImagePhoto"] ?? null);
        unset($dataToSave["base64ImagePhoto"]);

        if ($base64ImagePhoto) {
            $vcard->photo_url = $this->storeBase64AsFile($vcard, $base64ImagePhoto);
        }

        $vcard->save();
        try {
            $vcard = Vcard::findOrFail($dataToSave['phone_number']);
            // Your code here
        } catch (ModelNotFoundException $e) {
            // Handle the case where the Vcard with the given phone_number was not found
            // For example, return a response indicating that the Vcard does not exist.
            return response()->json(['error' => 'Vcard not found'], 404);
        }
        //$vcard->sendEmailVerificationNotification();

        // Fetch default categories
        $defaultCategories = DefaultCategory::all();

        // Create categories for the new vCard based on default categories
        foreach ($defaultCategories as $defaultCategory) {
            $category = Category::create([
                'vcard' => $dataToSave['phone_number'],
                'type' => $defaultCategory->type,
                'name' => $defaultCategory->name,
                'custom_options' => $defaultCategory->custom_options,
                'custom_data' => $defaultCategory->custom_data,
            ]);
            $category->save();

        }
        return new VcardResource($vcard);
    }
    // public function update_categories(Request $request, Vcard $vcard)
    // {
    //     $dataToSave = $request->validated();

    //     //update an existing categorie
    //     $vcard->categories()->update($dataToSave);

    //     return new VcardResource($vcard);
    // }

    public function update(UpdateVcardRequest $request, Vcard $vcard)
    {
        $dataToSave = $request->validated();

        $vcard->fill($dataToSave);
        $vcard->password = bcrypt($dataToSave['password']);

        // Delete previous photo file if a new file is uploaded
        $base64ImagePhoto = array_key_exists("base64ImagePhoto", $dataToSave) ?
            $dataToSave["base64ImagePhoto"] : null;
        if ($vcard->photo_url && $base64ImagePhoto) {
            if (Storage::exists('public/vcard_photos/' . $vcard->photo_url)) {
                Storage::delete('public/vcard_photos/' . $vcard->photo_url);
            }
            $vcard->photo_url = null;
        }

        // Create a new photo file from base64 content
        if ($base64ImagePhoto) {
            $vcard->photo_url = $this->storeBase64AsFile($vcard, $base64ImagePhoto);
        }

        $vcard->save();

        return new VCardResource($vcard);
    }
    public function update_password(UpdateAdminVcardPasswordRequest $request, Vcard $vcard)
    {
        // Check if the current user is authenticated
        // if (!Auth::check() || !Auth::user()->is($vcard)) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        // Proceed with updating the password
        $vcard->password = bcrypt($request->validated()['new_password']);
        $vcard->save();

        return new VcardResource($vcard);
    }
    public function show_me(Request $request)
    {
        $user = $request->user();
        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Retrieve the Vcard based on the authenticated user's phone_number
        $vcard = Vcard::where('phone_number', $user->id)->first();

        // Check if the Vcard is found and authorized
        if (!$vcard) {
            return response()->json(['message' => 'VCard not found'], 404);
        }

        // Return Vcard information using a resource class (assuming you have a VcardResource class)
        return new VcardResource($vcard);
    }



    public function destroy(Vcard $vcard)
    {
        // Delete the photo file
        if ($vcard->photo_url) {
            if (Storage::exists('public/vcard_photos/' . $vcard->photo_url)) {
                Storage::delete('public/vcard_photos/' . $vcard->photo_url);
            }
        }

        // Soft delete the vCard ,associated transactions, pairTransactions and categories(but categories are not in the vcard table only on the category table)
        $categories = Category::where('vcard', $vcard->phone_number)->get();
        foreach ($categories as $category) {
            $category->delete();
        }
        $vcard->transactions()->delete();
        $vcard->pairTransactions()->delete();
        $vcard->delete();

        return response()->json(['message' => 'VCard deleted successfully']);
    }

    public function restore($vcardId)
    {
        // Restore a soft-deleted vCard
        $vcard = Vcard::withTrashed()->find($vcardId);
        $vcard->restore();

        return response()->json(['message' => 'VCard restored successfully']);
    }
}
