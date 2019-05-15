<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Socialite;
use Exception;
use Illuminate\Http\Request;

class SocialAuthGoogleController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            
            $googleUser = Socialite::driver('google')->user();
            $existUser  = User::where('email',$googleUser->email)->first();

            if($existUser) {
                Auth::loginUsingId($existUser->id);
            }
            else {
                $user                  = new User;
                $user->name 	       = $googleUser->name;
                $user->first_name      = $googleUser->user['given_name'];
                $user->last_name       = $googleUser->user['family_name'];
                $user->email           = $googleUser->email;
                $user->google_id       = $googleUser->id;
                $user->avatar          = $googleUser->avatar;
                $user->avatar_original = $googleUser->avatar_original;
                $user->save();
                Auth::loginUsingId($user->id);
            }

            return redirect()->to('/home');
			// return response()->json(['google'=> $googleUser]);
            // return dd($googleUser);
        } 
        catch (Exception $e) {
            return 'error';
        }
    }
}
