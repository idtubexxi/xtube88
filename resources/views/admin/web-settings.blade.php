@extends('layouts.admin')

@section('title', 'Web Settings')
@section('page-title', 'Web Settings')

@section('content')
    <div class="max-w-3xl">
        <div class="card">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-6">Pengaturan Website</h3>

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

            <form method="POST" action="{{ route('admin.web.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                        Website</label>
                    <input type="text" id="site_name" name="site_name"
                        value="{{ old('site_name', $settings['site_name']->value ?? '') }}" required class="input-field">
                </div>

                <div class="mb-4">
                    <label for="site_description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Website</label>
                    <textarea id="site_description" name="site_description" rows="4" class="input-field">{{ old('site_description', $settings['site_description']->value ?? '') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="site_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email
                        Website</label>
                    <input type="email" id="site_email" name="site_email"
                        value="{{ old('site_email', $settings['site_email']->value ?? '') }}" required class="input-field">
                </div>

                <div class="mb-6">
                    <label for="site_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor
                        Telepon</label>
                    <input type="text" id="site_phone" name="site_phone"
                        value="{{ old('site_phone', $settings['site_phone']->value ?? '') }}" class="input-field">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary cursor-pointer w-full sm:w-auto">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
