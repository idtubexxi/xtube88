@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="max-w-3xl">
        <div class="card">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                @include('admin.categories.form')

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary cursor-pointer">
                        💾 Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
