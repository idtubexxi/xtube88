@extends('layouts.frontend')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <h1 class="text-9xl font-bold text-gray-300 dark:text-gray-700">404</h1>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-4 mb-2">Page Not Found</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                The page you're looking for doesn't exist or has been moved.
            </p>

            <div class="flex justify-center gap-4">
                <a href="/" class="px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                    Go Home
                </a>
                <button onclick="window.history.back()"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white rounded-full hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                    Go Back
                </button>
            </div>

            <div class="mt-12">
                <svg class="mx-auto w-64 h-64 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection
