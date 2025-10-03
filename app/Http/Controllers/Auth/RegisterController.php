<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
  public function showRegisterForm()
  {
    return view('auth.register');
  }
  public function Register(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:8|confirmed',
      'agree_terms' => 'required|accepted', // ✅ Untuk checkbox terms
    ]);

    try {
      $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'user',
        'email_verified_at' => null, // ✅ Untuk email verification nanti
      ]);

      // Optional: Send email verification
      // $user->sendEmailVerificationNotification();

      Auth::login($user);

      return redirect()->route('home')
        ->with('success', '🎉 Registration successful!');
    } catch (\Exception $e) {
      Log::error('Registration failed: ' . $e->getMessage());

      return back()->withErrors([
        'email' => 'Registration failed. Please try again.',
      ])->withInput();
    }
  }
}