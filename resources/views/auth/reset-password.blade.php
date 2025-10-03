@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="card">
        <h2 class="text-xl sm:text-2xl text-center font-bold text-gray-900 dark:text-white mb-6">Reset Password</h2>

        @if ($errors->any())
            <div
                class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                    class="input-field @error('email') border-red-500 @enderror" autocomplete="email"
                    placeholder="example@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New
                    Password</label>
                <input type="password" id="password" name="password" required
                    class="input-field @error('password') border-red-500 @enderror" autocomplete="new-password"
                    placeholder="Enter your new password">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="input-field">
            </div>

            <button type="submit" class="btn-primary cursor-pointer w-full">
                Reset Password
            </button>
    </div>
@endsection
