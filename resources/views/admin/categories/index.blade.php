@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Kelola kategori video</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary w-full sm:w-auto">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
        </a>
    </div>

    <div class="card">
        @if ($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Icon</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Name</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">
                                Videos</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">
                                Order</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition">
                                <td class="px-4 py-4">
                                    @if ($category->icon_svg)
                                        <div class="w-10 h-10 flex items-center justify-center rounded-lg"
                                            style="background-color: {{ $category->color }}20;">
                                            <div class="w-6 h-6" style="color: {{ $category->color }}">
                                                {!! $category->icon_svg !!}
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-700">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}
                                        </p>
                                        @if ($category->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                {{ Str::limit($category->description, 50) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 hidden md:table-cell">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $category->videos_count }}
                                        video(s)</span>
                                </td>
                                <td class="px-4 py-4 hidden sm:table-cell">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $category->order }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('admin.categories.toggle', $category) }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada kategori</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan kategori pertama.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
                        Tambah Kategori
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
