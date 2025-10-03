@extends('layouts.admin')

@section('title', 'Edit Video')
@section('page-title', 'Edit Video')

@section('content')
    <div class="max-w-4xl">
        <div class="card">
            <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.videos.form')

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.videos.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary cursor-pointer">
                        💾 Update Video
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
