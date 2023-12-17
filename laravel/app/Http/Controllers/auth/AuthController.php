<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $passportData = [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => $request->username,
            'password' => $request->password,
            'scope'         => '',
        ];

        request()->request->add($passportData);

        $request = Request::create(env('PASSPORT_SERVER_URL') . '/oauth/token', 'POST');
        $response = Route::dispatch($request);
        $errorCode = $response->getStatusCode();

        if (
            $errorCode == '200'
        ) {
            // Authentication successful, retrieve user data
            $responseData = json_decode((string) $response->content(), true);
            // Retrieve user type based on user data (modify this part based on your logic)
            $user = User::where('username', $passportData['username'])->first(); // Assuming you have access to the authenticated user
            $userType = $user->user_type; // Adjust this based on your actual user model property

            // Add user type to the response
            $responseData['user_type'] = $userType;
            //dd($responseData);
            return $responseData;
        } else {
            return response()->json(
                ['msg' => 'User credentials are invalid'],
                $errorCode
            );
        }
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
