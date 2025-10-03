@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Filters -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kategori
                </label>
                <select name="category_id" class="input-field">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field">
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full cursor-pointer">
                    🔍 Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <!-- Total Videos -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Videos</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_videos'] }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $stats['published_videos'] }} published</p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-full">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Views</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stats['total_views']) }}
                    </h3>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600 dark:text-green-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Categories</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $stats['total_categories'] }}</h3>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600 dark:text-purple-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Videos Per Day Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Videos Upload Trend</h3>
            <div style="height: 300px;">
                <canvas id="videosPerDayChart"></canvas>
            </div>
        </div>

        <!-- Views Per Day Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Views Trend</h3>
            <div style="height: 300px;">
                <canvas id="viewsPerDayChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Videos by Category Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Videos by Category</h3>
            <div style="height: 300px;">
                <canvas id="videosByCategoryChart"></canvas>
            </div>
        </div>

        <!-- Videos by Status Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Videos by Status</h3>
            <div style="height: 300px;">
                <canvas id="videosByStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Videos -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 5 Most Viewed Videos</h3>
        <div class="space-y-3">
            @forelse($topVideos as $video)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        @if ($video->thumbnail)
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}"
                                class="w-16 h-10 object-cover rounded flex-shrink-0">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $video->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $video->category->name }}</p>
                        </div>
                    </div>
                    <div class="text-right ml-3 flex-shrink-0">
                        <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                            {{ $video->formatted_views }} views
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-4">Belum ada video</p>
            @endforelse
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dark mode detection
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#9ca3af' : '#4b5563';
        const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

        // Chart default config
        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;

        // Videos Per Day Chart
        const videosPerDayCtx = document.getElementById('videosPerDayChart').getContext('2d');
        new Chart(videosPerDayCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($videosPerDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
                datasets: [{
                    label: 'Videos Uploaded',
                    data: {!! json_encode($videosPerDay->pluck('count')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Views Per Day Chart
        const viewsPerDayCtx = document.getElementById('viewsPerDayChart').getContext('2d');
        new Chart(viewsPerDayCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($viewsPerDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
                datasets: [{
                    label: 'Total Views',
                    data: {!! json_encode($viewsPerDay->pluck('total_views')) !!},
                    backgroundColor: '#10b981',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Videos by Category Chart
        const videosByCategoryCtx = document.getElementById('videosByCategoryChart').getContext('2d');
        new Chart(videosByCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($videosByCategory->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($videosByCategory->pluck('count')) !!},
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#84cc16'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Videos by Status Chart
        const videosByStatusCtx = document.getElementById('videosByStatusChart').getContext('2d');
        new Chart(videosByStatusCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($videosByStatus->pluck('status')->map(fn($s) => ucfirst($s))) !!},
                datasets: [{
                    data: {!! json_encode($videosByStatus->pluck('count')) !!},
                    backgroundColor: ['#f59e0b', '#10b981', '#6b7280']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
