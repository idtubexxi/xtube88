@extends('layouts.admin')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
    <div class="max-w-3xl">
        <div class="card">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-6">Pengaturan Akun</h3>

            @if ($errors->any())
                <div
                    class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.account.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <h4 class="text-sm sm:text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Profil</h4>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                            Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                            required class="input-field">
                    </div>

                    <div class="mb-4">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                            required class="input-field">
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <div class="mb-6">
                    <h4 class="text-sm sm:text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Ubah Password</h4>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-4">Kosongkan jika tidak ingin mengubah
                        password</p>

                    <div class="mb-4">
                        <label for="current_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Saat
                            Ini</label>
                        <input type="password" id="current_password" name="current_password" class="input-field">
                    </div>

                    <div class="mb-4">
                        <label for="new_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" class="input-field">
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password
                            Baru</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="input-field">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary w-full sm:w-auto">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endsection6">
@endsection
