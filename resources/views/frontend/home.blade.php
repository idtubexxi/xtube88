@extends('layouts.frontend')

@php
    $site_name = App\Models\WebSetting::where('key', 'site_name')->first();
@endphp

@section('title', $site_name ? $site_name->value : 'Home - XTube')

@section('content')
    <!-- Horizontal Banner Small (Top) -->
    @include('components.banner', [
        'type' => 'banner_rectangle_small',
        'class' => 'mb-6 flex w-full justify-center justify-center',
    ])

    <!-- Shorts Section -->
    @if ($shortVideos->count() > 0)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-red-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M10 9.35L15 12l-5 2.65zM12 6a9.77 9.77 0 018.82 5.5A9.77 9.77 0 0112 17a9.77 9.77 0 01-8.82-5.5A9.77 9.77 0 0112 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Shorts</h2>
            </div>

            <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                @foreach ($shortVideos as $short)
                    <a href="{{ route('watch', $short->slug) }}" class="flex-shrink-0 group">
                        <div class="w-40 sm:w-48">
                            <div class="relative aspect-[9/16] bg-gray-200 dark:bg-gray-800 rounded-xl overflow-hidden">
                                @if ($short->thumbnail)
                                    <img src="{{ asset('storage/' . $short->thumbnail) }}" alt="{{ $short->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Duration badge -->
                                <div
                                    class="absolute bottom-2 right-2 bg-black bg-opacity-80 text-white text-xs px-1.5 py-0.5 rounded">
                                    {{ $short->formatted_duration }}
                                </div>
                            </div>

                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white line-clamp-2">
                                {{ $short->title }}
                            </h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                {{ $short->formatted_views }} views
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif


    <!-- Horizontal Banner Small (Top) -->
    @include('components.banner', [
        'type' => 'banner_header_sticky',
        'class' => 'my-6 flex w-full justify-center justify-center',
    ])

    <!-- Videos Grid -->
    <div class="mb-6">
        @if ($selectedCategory)
            @php
                $category = $categories->firstWhere('id', $selectedCategory);
            @endphp
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $category ? $category->name : 'Videos' }}
                </h2>
                <a href="/" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Show All
                </a>
            </div>
        @endif
    </div>

    @if ($videos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($videos as $index => $video)
                <a href="{{ route('watch', $video->slug) }}" class="group">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Thumbnail -->
                        <div class="relative aspect-video bg-gray-200 dark:bg-gray-800">
                            @if ($video->thumbnail)
                                <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Duration -->
                            <div
                                class="absolute bottom-2 right-2 bg-black bg-opacity-80 text-white text-xs px-2 py-1 rounded">
                                {{ $video->formatted_duration }}
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2">
                                {{ $video->title }}
                            </h3>

                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400 space-x-2">
                                <span>{{ $video->formatted_views }} views</span>
                                <span>•</span>
                                <span>{{ $video->published_at->diffForHumans() }}</span>
                            </div>

                            <div class="mt-2 flex items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                    style="background-color: {{ $video->category->color }}20; color: {{ $video->category->color }}">
                                    {{ $video->category->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Insert Rectangle Banner after every 6 videos --}}
                @if (($index + 1) % 6 === 0)
                    <div class="sm:col-span-2 lg:col-span-1 flex items-center justify-center">
                        @include('components.banner', [
                            'type' => 'banner_rectangle_small',
                            'class' => 'w-full',
                        ])
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Native Banner 2 (Before Pagination) -->
        <div class="my-8">
            @include('components.banner', ['type' => 'banner_native_2', 'class' => 'flex justify-center'])
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $videos->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                </path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No videos found</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Try selecting a different category or check back later.
            </p>
        </div>
    @endif

    <!-- Native Banner 1 -->
    @include('components.banner', ['type' => 'banner_native_1', 'class' => 'mb-6 flex justify-center'])

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
