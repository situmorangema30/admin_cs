<?php

namespace App\Http\Controllers;

use App\Models\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        $formFieldsAuth = [
            [
                "name" => "username",
                "label" => "Username",
                "fieldType" => "text",
            ],
            [
                "name" => "password",
                "label" => "Password",
                "fieldType" => "password",
            ],
        ];

        return view('auth.index', [
            'formFields' => $formFieldsAuth,
        ]);
    }

    public function login(Request $request)
    {
        $rules = $request->validate([
            'username' => 'required|max:45',
            'password' => 'required|max:250',
        ]);

        if (Auth::attempt($rules)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
