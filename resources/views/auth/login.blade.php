@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="card">
        <h2 class="text-xl sm:text-2xl text-center font-bold text-gray-900 dark:text-white mb-6">Login to Your Account</h2>

        @if ($errors->any())
            <div
                class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div
                class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="input-field @error('email') border-red-500 @enderror" autocomplete="email" placeholder="example@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="input-field @error('password') border-red-500 @enderror" autocomplete="current-password" placeholder="Enter your password">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-3 sm:space-y-0">
                <label class="flex items-center">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remeber Me</span>
                </label>

                <a href="{{ route('password.request') }}"
                    class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="btn-primary cursor-pointer w-full">
                Login
            </button>
            <div class="mt-4 text-sm flex flex-col justify-center items-center text-gray-600 dark:text-gray-400">
                <div class="mt-4">
                    Don't have an account?
                </div>
                <a href="{{ route('register') }}"
                    class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                    Register here
                </a>
            </div>
        </form>
    </div>
@endsection
