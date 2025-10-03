@extends('layouts.admin')

@section('title', 'Tambah Video')
@section('page-title', 'Tambah Video')

@section('content')
    <div class="max-w-4xl">
        <div class="card">
            <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.videos.form')

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.videos.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary cursor-pointer">
                        💾 Simpan Video
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
