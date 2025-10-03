@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="card">
        <h2 class="text-xl sm:text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Sign Up for Free</h2>

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

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full
                    Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name" class="input-field @error('name') border-red-500 @enderror" placeholder="John Doe">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email" class="input-field @error('email') border-red-500 @enderror"
                    placeholder="example@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="input-field @error('password') border-red-500 @enderror" autocomplete="current-password"
                    placeholder="Enter your password">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Confirm Password
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="input-field"
                    placeholder="Repeat your password">
            </div>

            <!-- Terms Agreement -->
            <div>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="agree_terms" class="ms-2 text-sm text-gray-600 dark:text-gray-300">
                        I agree to the <a href="{{ route('register') }}"
                            class="text-blue-600 hover:underline dark:text-blue-400">Terms and Conditions</a>
                    </label>
                </div>
                @error('agree_terms')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary cursor-pointer w-full">
                Sign Up
            </button>
            <div class="flex items-center justify-center text-center py-5 border-b border-gray-200 dark:border-gray-500">
                <p class="ml-2 text-xs text-gray-600 dark:text-gray-400">By signing up, you agree to the Terms and
                    Conditions and Privacy Policy, including Cookie Use.</p>
            </div>

            <div class="mt-4 text-sm flex flex-col justify-center items-center text-gray-600 dark:text-gray-400">
                <div class="mt-4">
                    Already have an account?

                </div>
                <a href="{{ route('login') }}"
                    class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                    Login here
                </a>
            </div>
        </form>
    </div>
@endsection
