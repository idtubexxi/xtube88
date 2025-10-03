@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <div class="card">
        <h2 class="text-xl text-center sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">Forgot Password?</h2>
        <p class="text-gray-600 text-center dark:text-gray-400 mb-6 text-sm sm:text-base">Enter your email and we will send
            you a
            password reset link.</p>

        @if (session('status'))
            <div
                class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="input-field @error('email') border-red-500 @enderror" placeholder="example@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary cursor-pointer w-full mb-4">
                Send Password Reset Link
            </button>

            <a href="{{ route('login') }}"
                class="block text-center text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                Back to Login
            </a>
        </form>
    </div>
@endsection
