@if ($errors->any())
    <div
        class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <!-- Title -->
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Judul Video <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title" value="{{ old('title', $video->title ?? '') }}" required
            class="input-field" placeholder="Contoh: Tutorial Laravel untuk Pemula">
    </div>

    <!-- Category -->
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Kategori <span class="text-red-500">*</span>
        </label>
        <select id="category_id" name="category_id" required class="input-field">
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $video->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Deskripsi
        </label>
        <textarea id="description" name="description" rows="4" class="input-field"
            placeholder="Jelaskan tentang video ini...">{{ old('description', $video->description ?? '') }}</textarea>
    </div>

    <!-- Type Selection -->
    <div>
        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Type <span class="text-red-500">*</span>
        </label>
        <select id="type" name="type" required class="input-field w-full">
            <option value="url" {{ old('type', $video->type ?? 'url') == 'url' ? 'selected' : '' }}>
                URL
            </option>
            <option value="iframe" {{ old('type', $video->type ?? '') == 'iframe' ? 'selected' : '' }}>
                IFRAME
            </option>
        </select>
    </div>

    <!-- Video IFRAME Field (Initially shown/hidden based on type) -->
    <div id="iframe-field" class="{{ old('type', $video->type ?? 'url') == 'iframe' ? '' : 'hidden' }}">
        <label for="iframe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Video IFRAME <span class="text-red-500">*</span>
        </label>
        <textarea id="iframe" name="iframe" rows="4" class="input-field font-mono text-sm w-full"
            placeholder='<iframe src="..."></iframe>'>{{ old('iframe', $video->iframe ?? '') }}</textarea>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Embed code IFRAME dari platform seperti YouTube, Vimeo, dll.
        </p>
    </div>

    <!-- Cloudinary Info -->
    <div id="url-field" class="{{ old('type', $video->type ?? 'url') == 'url' ? '' : 'hidden' }} space-y-4">
        <div class="grid grid-cols-1 w-full gap-4">
            <div>
                <label for="cloudinary_public_id"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Cloudinary Public ID <span class="italic">(Optional)</span>
                </label>
                <input type="text" id="cloudinary_public_id" name="cloudinary_public_id"
                    value="{{ old('cloudinary_public_id', $video->cloudinary_public_id ?? '') }}"
                    class="input-field font-mono text-sm" placeholder="videos/abc123xyz">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    ID video dari Cloudinary
                </p>
            </div>

            <div>
                <label for="cloudinary_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Video URL <span class="text-red-500">*</span>
                </label>
                <input type="url" id="cloudinary_url" name="cloudinary_url"
                    value="{{ old('cloudinary_url', $video->cloudinary_url ?? '') }}"
                    class="input-field font-mono text-sm" placeholder="https://res.cloudinary.com/...">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Full URL video format (mp4, html5, webm, dll)
                </p>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 flex-shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-700 dark:text-blue-400">
                    <p class="font-medium mb-1">Cara Upload ke Cloudinary:</p>
                    <ol class="list-decimal list-inside space-y-1 text-xs">
                        <li>Login ke <a href="https://cloudinary.com" target="_blank"
                                class="underline">cloudinary.com</a>
                        </li>
                        <li>Upload video Anda</li>
                        <li>Copy Public ID dan URL yang diberikan</li>
                        <li>Paste di form ini</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Thumbnail -->
    <div>
        <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Thumbnail Video
        </label>

        @if (isset($video) && $video->thumbnail)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Current Thumbnail"
                    class="w-48 h-auto rounded-lg border border-gray-300 dark:border-gray-600">

                {{-- FIX: No nested form! Use button with JavaScript instead --}}
                <button type="button"
                    onclick="deleteThumbnail('{{ route('admin.videos.thumbnail.delete', $video) }}')"
                    class="mt-2 text-xs px-3 py-1 bg-red-100 cursor-pointer dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                    🗑️ Hapus Thumbnail
                </button>
            </div>
        @endif

        <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/jpg"
            class="input-field" onchange="previewThumbnail(event)">
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            PNG, JPG (Max: 2MB) - Recommended: 1280x720px (16:9)
        </p>

        <!-- Preview -->
        <div id="thumbnailPreview" class="mt-3 hidden">
            <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
            <img id="thumbnailPreviewImage" class="w-48 h-auto rounded-lg border border-gray-300 dark:border-gray-600"
                alt="Thumbnail Preview">
        </div>
    </div>

    <!-- Duration -->
    <div>
        <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Durasi (detik) <span class="text-red-500">*</span>
        </label>
        <input type="number" id="duration" name="duration" value="{{ old('duration', $video->duration ?? 0) }}"
            required min="0" class="input-field w-32" placeholder="600">
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Durasi video dalam detik (contoh: 600 = 10 menit)
        </p>
    </div>

    <!-- Tags -->
    <div>
        <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Tags
        </label>
        <input type="text" id="tags" name="tags"
            value="{{ old('tags', isset($video) && $video->tags ? implode(', ', $video->tags) : '') }}"
            class="input-field" placeholder="tutorial, laravel, php">
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Pisahkan dengan koma (contoh: tutorial, laravel, php)
        </p>
    </div>

    <!-- Status -->
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Status <span class="text-red-500">*</span>
        </label>
        <select id="status" name="status" required class="input-field w-full">
            <option value="draft" {{ old('status', $video->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                Draft
            </option>
            <option value="published" {{ old('status', $video->status ?? '') == 'published' ? 'selected' : '' }}>
                Published
            </option>
            <option value="private" {{ old('status', $video->status ?? '') == 'private' ? 'selected' : '' }}>
                Private
            </option>
        </select>
        <div class="mt-2 text-xs space-y-1">
            <p class="text-gray-600 dark:text-gray-400">
                <span class="font-medium">Draft:</span> Hanya admin yang bisa lihat
            </p>
            <p class="text-gray-600 dark:text-gray-400">
                <span class="font-medium">Published:</span> Public, semua bisa lihat
            </p>
            <p class="text-gray-600 dark:text-gray-400">
                <span class="font-medium">Private:</span> Hanya dengan link langsung
            </p>
        </div>
    </div>
</div>

<script>
    const typeSelect = document.getElementById('type');
    const urlField = document.getElementById('url-field');
    const iframeField = document.getElementById('iframe-field');

    function toggleFields(type) {
        const cloudinaryPublicIdInput = document.getElementById('cloudinary_public_id');
        const cloudinaryUrlInput = document.getElementById('cloudinary_url');
        const iframeInput = document.getElementById('iframe');

        if (type === 'url') {
            urlField.classList.remove('hidden');
            iframeField.classList.add('hidden');

            // Set required for cloudinary fields
            cloudinaryPublicIdInput.required = true;
            cloudinaryUrlInput.required = true;
            iframeInput.required = false;
        } else {
            urlField.classList.add('hidden');
            iframeField.classList.remove('hidden');

            // Remove required for cloudinary fields
            cloudinaryPublicIdInput.required = false;
            cloudinaryUrlInput.required = false;
            iframeInput.required = true;
        }
    }

    // Initialize on page load
    toggleFields(typeSelect.value);

    // Add event listener for change
    typeSelect.addEventListener('change', function() {
        toggleFields(this.value);
    });

    function previewThumbnail(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('thumbnailPreview').classList.remove('hidden');
                document.getElementById('thumbnailPreviewImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    // Function to delete thumbnail via AJAX
    function deleteThumbnail(url) {
        if (!confirm('Yakin ingin menghapus thumbnail?')) {
            return;
        }

        fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                        document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    // Reload page to show updated state
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghapus thumbnail. Silakan coba lagi.');
            });
    }
</script>
