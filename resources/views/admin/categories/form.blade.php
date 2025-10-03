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
    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nama Kategori <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required
            class="input-field" placeholder="Contoh: Music, Gaming, Education">
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Deskripsi
        </label>
        <textarea id="description" name="description" rows="3" class="input-field"
            placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <!-- Icon SVG -->
    <div>
        <label for="icon_svg" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Icon SVG
        </label>
        <textarea id="icon_svg" name="icon_svg" rows="4" class="input-field font-mono text-xs"
            placeholder='<svg viewBox="0 0 24 24" fill="currentColor">...</svg>'>{{ old('icon_svg', $category->icon_svg ?? '') }}</textarea>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            💡 Paste kode SVG dari <a href="https://heroicons.com" target="_blank"
                class="text-primary-600 hover:underline">Heroicons</a> atau
            <a href="https://lucide.dev" target="_blank" class="text-primary-600 hover:underline">Lucide</a>
        </p>

        <!-- Icon Preview -->
        <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
            <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
            <div id="iconPreview"
                class="w-12 h-12 flex items-center justify-center rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                @if (isset($category) && $category->icon_svg)
                    <div style="color: {{ $category->color ?? '#3b82f6' }}">
                        {!! $category->icon_svg !!}
                    </div>
                @else
                    <span class="text-xs text-gray-400">No icon</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Color -->
    <div>
        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Warna <span class="text-red-500">*</span>
        </label>
        <div class="flex items-center space-x-3">
            <input type="color" id="color" name="color"
                value="{{ old('color', $category->color ?? '#3b82f6') }}" required
                class="h-10 w-20 rounded border border-gray-300 dark:border-gray-600 cursor-pointer">
            <input type="text" id="colorHex" value="{{ old('color', $category->color ?? '#3b82f6') }}"
                class="input-field w-32" readonly>
        </div>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Warna akan digunakan untuk icon dan accent kategori
        </p>
    </div>

    <!-- Order -->
    <div>
        <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Urutan Tampilan <span class="text-red-500">*</span>
        </label>
        <input type="number" id="order" name="order" value="{{ old('order', $category->order ?? 0) }}" required
            min="0" class="input-field w-32" placeholder="0">
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Urutan kategori di menu (0 = paling atas)
        </p>
    </div>

    <!-- Status -->
    <div class="flex items-center">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
            Aktifkan kategori ini
        </label>
    </div>
</div>

<script>
    // Live preview icon and color
    document.addEventListener('DOMContentLoaded', function() {
        const iconInput = document.getElementById('icon_svg');
        const colorInput = document.getElementById('color');
        const colorHex = document.getElementById('colorHex');
        const preview = document.getElementById('iconPreview');

        // Update icon preview
        iconInput?.addEventListener('input', function() {
            if (this.value.trim()) {
                preview.innerHTML = `<div style="color: ${colorInput.value}">${this.value}</div>`;
            } else {
                preview.innerHTML = '<span class="text-xs text-gray-400">No icon</span>';
            }
        });

        // Update color preview
        colorInput?.addEventListener('input', function() {
            colorHex.value = this.value;
            const icon = preview.querySelector('div');
            if (icon) {
                icon.style.color = this.value;
            }
        });
    });
</script>
