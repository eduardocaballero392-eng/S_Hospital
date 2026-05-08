<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (view()->exists('medico.dashboard')) {
            return view('medico.dashboard', [
                'usuario' => Auth::user(),
            ]);
        }

        return redirect()->route('home');
    }
}
