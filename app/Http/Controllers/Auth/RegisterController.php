<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $role = Role::where('role', 'User')->first();
        $roleId = $role ? $role->roleId : 2; // Default to 2 if 'User' role not found

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'designation' => $request->designation,
            'roleId' => $roleId,
            'isAdmin' => 0,
            'status' => 0, // For Approval
        ]);

        // Email Admins
        try {
            $admins = User::where('isAdmin', 1)->get();
            if ($admins->count() > 0) {
                \Illuminate\Support\Facades\Mail::to($admins)->send(new \App\Mail\NewUserRegistration($user));
            }
        } catch (\Exception $e) {
            // Log error or ignore if mail is not configured
            \Illuminate\Support\Facades\Log::error('Admin registration email failed: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending approval.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'designation' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
