@extends('layouts.frontend')

@php
    $site_name = App\Models\WebSetting::where('key', 'site_name')->value('value') ?? 'XTube';
    $page_title = 'Search Results' . (isset($query) && $query ? ' - ' . $query : '') . ' - ' . $site_name;
@endphp

@section('title', $page_title)

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            Search Results
        </h1>
        @if (isset($query) && $query)
            <p class="text-gray-600 dark:text-gray-400">
                Found {{ $videos->total() }} result(s) for "<span class="font-semibold">{{ $query }}</span>"
            </p>
        @endif
    </div>

    <!-- Horizontal Banner Small (Top) -->
    @include('components.banner', [
        'type' => 'banner_rectangle_small',
        'class' => 'mb-6 flex w-full justify-center justify-center',
    ])

    @if ($videos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($videos as $video)
                <a href="{{ route('watch', $video->slug) }}" class="group">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
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

                            <div
                                class="absolute bottom-2 right-2 bg-black bg-opacity-80 text-white text-xs px-2 py-1 rounded">
                                {{ $video->formatted_duration }}
                            </div>
                        </div>

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
            @endforeach
        </div>

        <div class="mt-8">
            {{ $videos->appends(['q' => $query])->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No results found</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                @if (isset($query) && $query)
                    We couldn't find any videos matching "{{ $query }}"
                @else
                    Please enter a search query
                @endif
            </p>
        </div>
    @endif

    <!-- Native Banner 1 -->
    @include('components.banner', ['type' => 'banner_native_1', 'class' => 'mb-6 flex justify-center'])

@endsection
