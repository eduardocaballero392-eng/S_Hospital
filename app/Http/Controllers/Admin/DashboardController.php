<?php

namespace App\Http\Controllers\Admin;

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
        // Evita 500 si aún no existe vista admin dedicada.
        if (view()->exists('admin.dashboard')) {
            return view('admin.dashboard', [
                'usuario' => Auth::user(),
            ]);
        }

        return redirect()->route('home');
    }
}
