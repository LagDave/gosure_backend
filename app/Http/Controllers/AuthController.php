<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;

class AuthController extends Controller
{

    /** returns the google redirect url in which users will sign in */
    public function getGoogleRedirectURL(){
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    /** creates user (if non existent) then returns user data with the access token for the api routes */
    /** requires to be called STRICTLY WITH specific google auth parameters - cannot be fetched directly */
    public function loginUser(){ 

        /** get the google user via the given GET parameters */
        $socialiteUser = Socialite::driver('google')->stateless()->user();

        /** get user data from database if existent; otherwise, create */
        $user = User::query()->firstOrCreate(
            [ 'email' => $socialiteUser->email ],
            [
                'email_verified_at' => now(),
                'name' => $socialiteUser->name,
                'gauth_id'=> $socialiteUser->id,
                'gauth_type'=> 'google',
                'password' => Str::password(10)
            ]
        );

        /** return user data with the access token */
        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('google-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);

    }

    public function displayUser() {
        return Auth::user();
    }

    public function logoutUser(){
        Auth::user()->currentAccessToken()->delete();
        return 'logged out';
    }

}
