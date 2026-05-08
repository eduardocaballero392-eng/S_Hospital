<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rol;
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

    protected function authenticated(Request $request, $user)
    {
        if ((int) $user->rol_id === Rol::ADMIN) {
            return redirect('/admin/dashboard');
        }

        if ((int) $user->rol_id === Rol::MEDICO) {
            return redirect('/medico/dashboard');
        }

        return redirect('/paciente/dashboard');
    }

    //usar email para login 

    public function username()
    {
        return 'email';
    }

}
