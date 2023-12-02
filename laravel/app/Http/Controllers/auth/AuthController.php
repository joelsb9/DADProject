<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Determine the user provider based on the username format
        $userProvider = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'users' : 'vcards';

        // Set up the appropriate user provider dynamically
        config(['auth.defaults.provider' => $userProvider]);

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
            return json_decode((string) $response->content(), true);
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
