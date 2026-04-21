<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('backpack')->check()) {
            return redirect()->intended('/admin/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('backpack')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('backpack')->user();
            
            if ($user->status != 1) {
                Auth::guard('backpack')->logout();
                
                $message = 'Your account is pending approval.';
                if ($user->status == 100) {
                    $message = 'Your account has been rejected.';
                }
                
                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('backpack')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
