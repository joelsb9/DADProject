<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use Illuminate\Http\Request;
use App\Services\Base64Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VcardResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVcardRequest;
use App\Http\Requests\UpdateVcardRequest;
use App\Http\Requests\UpdateAdminVcardPasswordRequest;

class VcardController extends Controller
{
    private function storeBase64AsFile(VCard $vcard, string $base64String)
    {
        $targetDir = storage_path('app/public/vcard_photos');
        $newfilename = $vcard->id . "_" . rand(10000, 19999);
        $base64Service = new Base64Services();
        return $base64Service->saveFile($base64String, $targetDir, $newfilename);
    }

    public function index()
    {
        return VcardResource::collection(Vcard::withTrashed()->get());
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

        return new VcardResource($vcard);
    }

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
        $vcard = $request->user('vcard');
        if ($vcard) {
            return new VcardResource($vcard);
        } else {
            return response()->json(['message' => 'VCard not found'], 404);
        }
    }

    public function delete(Vcard $vcard)
    {
        // Delete the photo file
        if ($vcard->photo_url) {
            if (Storage::exists('public/vcard_photos/' . $vcard->photo_url)) {
                Storage::delete('public/vcard_photos/' . $vcard->photo_url);
            }
        }

        // Soft delete the vCard and associated transactions
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
