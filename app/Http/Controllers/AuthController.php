<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){ 
        $user = Socialite::driver('google')->user();  
        $finduser = User::where('gauth_id', $user->id)->first();
      
        if($finduser){
    
            Auth::login($finduser);
    
            return redirect('/dashboard');
    
        }else{
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'gauth_id'=> $user->id,
                'gauth_type'=> 'google',
                'password' => encrypt('admin@123')
            ]);
    
            Auth::login($newUser);
    
            return $user;
        }
        
        return $user;
    }

    public function displayUser() {
        return Auth::user();
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }

}
