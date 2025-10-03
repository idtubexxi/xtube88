@extends('layouts.admin')

@section('title', 'Brand Settings')
@section('page-title', 'Brand Settings')

@section('content')
    <div class="max-w-4xl">
        <div class="card mb-6">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 mr-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Logo & Favicon</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Upload logo dan favicon untuk website
                        Anda</p>
                </div>
            </div>

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

            <form method="POST" action="{{ route('admin.brand.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Logo Website
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center bg-gray-50 dark:bg-gray-900/50">
                                @if (isset($settings['site_logo']) && $settings['site_logo']->value)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $settings['site_logo']->value) }}"
                                            alt="Current Logo" class="max-h-32 mx-auto object-contain">
                                    </div>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <form method="POST" action="{{ route('admin.brand.logo.delete') }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus logo?')"
                                                class="text-xs cursor-pointer px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                                🗑️ Hapus Logo
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Belum ada logo</p>
                                @endif

                                <div class="mt-4">
                                    <label
                                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        Pilih Logo
                                        <input type="file" name="logo" class="hidden"
                                            accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                            onchange="previewLogo(event)">
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        PNG, JPG, SVG (Max: 2MB)
                                    </p>
                                </div>

                                <!-- Preview -->
                                <div id="logoPreview" class="mt-4 hidden">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                                    <img id="logoPreviewImage" class="max-h-24 mx-auto object-contain rounded"
                                        alt="Logo Preview">
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                            <p class="text-xs text-blue-700 dark:text-blue-400">
                                <strong>💡 Tips Logo:</strong><br>
                                • Gunakan PNG dengan background transparan<br>
                                • Ukuran ideal: 200x60px atau ratio 3:1<br>
                                • File maksimal 2MB
                            </p>
                        </div>
                    </div>

                    <!-- Favicon Upload -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Favicon
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center bg-gray-50 dark:bg-gray-900/50">
                                @if (isset($settings['site_favicon']) && $settings['site_favicon']->value)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $settings['site_favicon']->value) }}"
                                            alt="Current Favicon" class="w-16 h-16 mx-auto object-contain">
                                    </div>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <form method="POST" action="{{ route('admin.brand.favicon.delete') }}"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus favicon?')"
                                                class="text-xs px-3 cursor-pointer py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                                🗑️ Hapus Favicon
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                        </path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Belum ada favicon</p>
                                @endif

                                <div class="mt-4">
                                    <label
                                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        Pilih Favicon
                                        <input type="file" name="favicon" class="hidden"
                                            accept="image/x-icon,image/png,image/jpg" onchange="previewFavicon(event)">
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        ICO, PNG (Max: 1MB)
                                    </p>
                                </div>

                                <!-- Preview -->
                                <div id="faviconPreview" class="mt-4 hidden">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                                    <img id="faviconPreviewImage" class="w-12 h-12 mx-auto object-contain rounded"
                                        alt="Favicon Preview">
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                            <p class="text-xs text-blue-700 dark:text-blue-400">
                                <strong>💡 Tips Favicon:</strong><br>
                                • Ukuran ideal: 32x32px atau 64x64px<br>
                                • Format: ICO atau PNG<br>
                                • File maksimal 1MB
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary cursor-pointer w-full sm:w-auto">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').classList.remove('hidden');
                    document.getElementById('logoPreviewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function previewFavicon(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('faviconPreview').classList.remove('hidden');
                    document.getElementById('faviconPreviewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
