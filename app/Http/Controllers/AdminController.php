<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // แสดงฟอร์มเข้าสู่ระบบสำหรับ Admin
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // การเข้าสู่ระบบสำหรับ Admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ใช้ guard สำหรับ Admin
        if (Auth::guard('librarians')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/books');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('librarians')->logout();  // Log out the librarian
        $request->session()->invalidate();   // Invalidate the session
        $request->session()->regenerateToken();  // Regenerate CSRF token to prevent attacks
        
        return redirect()->route('admin.login');  // Redirect to the login page
    }
}
