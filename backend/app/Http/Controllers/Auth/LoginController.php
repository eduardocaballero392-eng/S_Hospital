<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    
      protected $redirectTo = '/home';

     
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
       
    }

    //redigir segun el rol
    protected function authenticated(Request $request, $user)
    {
         if ($user->rol_id == 1) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/paciente/dashboard');
        }
    }

    //usar email para login 

    public function username()
    {
        return 'email';
    }

}
