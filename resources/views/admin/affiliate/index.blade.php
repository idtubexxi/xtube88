@extends('layouts.admin')

@section('title', 'Affiliate & Ads Settings')
@section('page-title', 'Affiliate & Ads Management')

@section('content')
    <div class="max-w-5xl">
        <!-- Info Banner -->
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-700 dark:text-blue-400">
                    <p class="font-medium mb-1">💡 Panduan Penggunaan:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Paste kode HTML/JavaScript dari affiliate network Anda</li>
                        <li>Toggle switch untuk enable/disable setiap banner</li>
                        <li>Banner hanya muncul di frontend jika sudah enabled</li>
                        <li>Histats code akan muncul di semua halaman frontend</li>
                    </ul>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.affiliate.update') }}">
            @csrf
            @method('PUT')

            <!-- Header Affiliate Code -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    Header Affiliate Code
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Code/URL (Header)
                        </label>
                        <textarea name="header_affiliate_code" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script> atau https://link.com">{{ old('header_affiliate_code', $settings['header_affiliate_code']->value ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Kode ini akan dimuat di header semua halaman frontend
                        </p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="enable_header_affiliate_code" value="1"
                            {{ $settings['header_affiliate_code']->is_enabled ?? false ? 'checked' : '' }}
                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Enable Header Affiliate Code
                        </label>
                    </div>
                </div>
            </div>

            <!-- Offer Links -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                        </path>
                    </svg>
                    Offer Links
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Offer Link 1
                        </label>
                        <input type="url" name="offer_link_1"
                            value="{{ old('offer_link_1', $settings['offer_link_1']->value ?? '') }}" class="input-field"
                            placeholder="https://affiliate.com/offer1">
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_offer_link_1" value="1"
                                {{ $settings['offer_link_1']->is_enabled ?? true ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Offer Link 2
                        </label>
                        <input type="url" name="offer_link_2"
                            value="{{ old('offer_link_2', $settings['offer_link_2']->value ?? '') }}" class="input-field"
                            placeholder="https://affiliate.com/offer2">
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_offer_link_2" value="1"
                                {{ $settings['offer_link_2']->is_enabled ?? true ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Ads - Horizontal -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                        </path>
                    </svg>
                    Banner Ads - Horizontal
                </h3>

                <div class="space-y-6">
                    <!-- Horizontal Small -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Horizontal Small (728x90 / Leaderboard)
                        </label>
                        <textarea name="banner_horizontal_small" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_horizontal_small', $settings['banner_horizontal_small']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_horizontal_small" value="1"
                                {{ $settings['banner_horizontal_small']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable Banner</label>
                        </div>
                    </div>

                    <!-- Horizontal Large -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Horizontal Large (970x90 / Large Leaderboard)
                        </label>
                        <textarea name="banner_horizontal_large" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_horizontal_large', $settings['banner_horizontal_large']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_horizontal_large" value="1"
                                {{ $settings['banner_horizontal_large']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable Banner</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Ads - Rectangle -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                        </path>
                    </svg>
                    Banner Ads - Rectangle
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Rectangle Small -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rectangle Small (300x250)
                        </label>
                        <textarea name="banner_rectangle_small" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_rectangle_small', $settings['banner_rectangle_small']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_rectangle_small" value="1"
                                {{ $settings['banner_rectangle_small']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>

                    <!-- Rectangle Large -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rectangle Large (336x280)
                        </label>
                        <textarea name="banner_rectangle_large" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_rectangle_large', $settings['banner_rectangle_large']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_rectangle_large" value="1"
                                {{ $settings['banner_rectangle_large']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Ads - Native -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                        </path>
                    </svg>
                    Native Ads
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Native Ads 1
                        </label>
                        <textarea name="banner_native_1" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_native_1', $settings['banner_native_1']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_native_1" value="1"
                                {{ $settings['banner_native_1']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Native Ads 2
                        </label>
                        <textarea name="banner_native_2" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_native_2', $settings['banner_native_2']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_native_2" value="1"
                                {{ $settings['banner_native_2']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Ads -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    Special Ads (Popup & Sticky)
                </h3>

                <div class="space-y-6">
                    <!-- Popup Centered -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Popup Floating Centered
                        </label>
                        <textarea name="banner_popup_centered" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_popup_centered', $settings['banner_popup_centered']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_popup_centered" value="1"
                                {{ $settings['banner_popup_centered']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>

                    <!-- Bottom Sticky -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bottom Sticky Ads
                        </label>
                        <textarea name="banner_bottom_sticky" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_bottom_sticky', $settings['banner_bottom_sticky']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_bottom_sticky" value="1"
                                {{ $settings['banner_bottom_sticky']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>

                    <!-- Header Sticky -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Header Sticky Ads
                        </label>
                        <textarea name="banner_header_sticky" rows="3" class="input-field font-mono text-xs"
                            placeholder="<script>
                                ...
                            </script>">{{ old('banner_header_sticky', $settings['banner_header_sticky']->value ?? '') }}</textarea>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="enable_banner_header_sticky" value="1"
                                {{ $settings['banner_header_sticky']->is_enabled ?? false ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                            <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">Enable</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histats Analytics -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Histats Analytics
                </h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Histats Code
                    </label>
                    <textarea name="histats_code" rows="4" class="input-field font-mono text-xs"
                        placeholder="<script>
                            ...
                        </script>">{{ old('histats_code', $settings['histats_code']->value ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Paste kode tracking dari <a href="https://www.histats.com" target="_blank"
                            class="text-primary-600 hover:underline">Histats.com</a>
                    </p>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" name="enable_histats_code" value="1"
                            {{ $settings['histats_code']->is_enabled ?? false ? 'checked' : '' }}
                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Enable Histats Analytics
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="btn-primary cursor-pointer">
                    💾 Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection
