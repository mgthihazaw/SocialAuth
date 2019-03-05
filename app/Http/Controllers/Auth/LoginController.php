<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('facebook')->user();

        // dd($userSocial->name);

        $user=User::where('email',$userSocial->email)->first();

        if($user){
          if( Auth::loginUsingId($user->id)){
            return redirect()->route('home');
          }
        }
         $userSignup=User::create([
            'name'     => $userSocial['name'],
            'email'    => $userSocial['email'],
            'password' => Hash::make('7777777'),
            // 'avatar'   => $userSocial->avatar,
            // 'fbProfile'=>$userSocial->$user->avatar_original,
            // 'gender'   =>$userSocial->user->gender,
        ]);
         if($userSignup){
            $user=User::where('email',$userSocial->email)->first();
            if( Auth::loginUsingId($user->id)){
            return redirect()->route('home');
          }
         }
    }
}
