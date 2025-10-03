@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
    <div class="max-w-3xl">
        <div class="card">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')
                @include('admin.categories.form')

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary cursor-pointer">
                        💾 Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
