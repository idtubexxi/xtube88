<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $remember = $request->filled('remember');

    if (Auth::attempt($credentials, $remember)) {
      $request->session()->regenerate();

      $user = Auth::user();

      // Check user role and redirect accordingly
      if ($user->isAdmin()) {
        // For admin, always go to admin dashboard unless there's specific intended URL
        $intended = session('url.intended');

        // If intended URL is admin route, use it, otherwise go to admin dashboard
        if ($intended && str_contains($intended, 'admin')) {
          return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->route('admin.dashboard');
      }

      // For regular users
      return redirect()->intended(route('home'));
    }

    return back()->withErrors([
      'email' => 'Incorrect email or password.',
    ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
  }
}