<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideosController extends Controller
{
  public function index(Request $request)
  {
    $query = Video::with(['category', 'user']);

    // Filter by category
    if ($request->filled('category')) {
      $query->where('category_id', $request->category);
    }

    // Filter by status
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // Search
    if ($request->filled('search')) {
      $query->where('title', 'like', '%' . $request->search . '%');
    }

    $videos = $query->latest()->paginate(20);
    $categories = Category::active()->ordered()->get();

    return view('admin.videos.index', compact('videos', 'categories'));
  }

  public function create()
  {
    $categories = Category::active()->ordered()->get();
    return view('admin.videos.create', compact('categories'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'category_id' => 'required|exists:categories,id',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|in:url,iframe',
      'cloudinary_public_id' => [
        'required_if:type,url',
        'nullable',
        'string',
        'regex:/^[a-zA-Z0-9\/\._-]+$/' // Basic Cloudinary Public ID format
      ],
      'cloudinary_url' => 'required_if:type,url|nullable|url',
      'iframe' => 'required_if:type,iframe|nullable|string',
      'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'duration' => 'required|integer|min:0',
      'tags' => 'nullable|string',
      'status' => 'required|in:draft,published,private',
    ]);

    // Upload thumbnail if provided
    if ($request->hasFile('thumbnail')) {
      $filename = time() . '_' . uniqid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
      $validated['thumbnail'] = $request->file('thumbnail')->storeAs('thumbnails', $filename, 'public');
    }

    // Convert tags string to array
    $validated['tags'] = $request->filled('tags')
      ? array_map('trim', explode(',', $request->tags))
      : null;

    // Set user_id
    $validated['user_id'] = Auth::id();

    // Set published_at if status is published
    if ($validated['status'] === 'published') {
      $validated['published_at'] = now();
    }

    Video::create($validated);

    return redirect()->route('admin.videos.index')
      ->with('success', '✅ Video berhasil ditambahkan!');
  }

  public function edit(Video $video)
  {
    $categories = Category::active()->ordered()->get();
    return view('admin.videos.edit', compact('video', 'categories'));
  }

  public function update(Request $request, Video $video)
  {
    $validated = $request->validate([
      'category_id' => 'required|exists:categories,id',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'type' => 'required|in:url,iframe',
      'cloudinary_public_id' => [
        'required_if:type,url',
        'nullable',
        'string',
        'regex:/^[a-zA-Z0-9\/\._-]+$/' // Basic Cloudinary Public ID format
      ],
      'cloudinary_url' => 'required_if:type,url|nullable|url',
      'iframe' => 'required_if:type,iframe|nullable|string',
      'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'duration' => 'required|integer|min:0',
      'tags' => 'nullable|string',
      'status' => 'required|in:draft,published,private',
    ]);

    // Upload new thumbnail if provided
    if ($request->hasFile('thumbnail')) {
      // Delete old thumbnail
      if ($video->thumbnail) {
        Storage::disk('public')->delete($video->thumbnail);
      }

      $filename = time() . '_' . uniqid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
      $validated['thumbnail'] = $request->file('thumbnail')->storeAs('thumbnails', $filename, 'public');
    }

    // Convert tags string to array
    $validated['tags'] = $request->filled('tags')
      ? array_map('trim', explode(',', $request->tags))
      : null;

    // Update slug only if title changed
    if ($validated['title'] !== $video->title) {
      $validated['slug'] = $this->generateUniqueSlug($validated['title'], $video->id);
    }

    // Set published_at if status changed to published
    if ($validated['status'] === 'published' && $video->status !== 'published') {
      $validated['published_at'] = now();
    } elseif ($validated['status'] !== 'published') {
      // Clear published_at if status changed from published
      $validated['published_at'] = null;
    }

    $video->update($validated);

    return redirect()->route('admin.videos.index')
      ->with('success', '✅ Video berhasil diperbarui!');
  }

  public function destroy(Video $video)
  {
    // Delete thumbnail
    if ($video->thumbnail) {
      Storage::disk('public')->delete($video->thumbnail);
    }

    $video->delete();

    return redirect()->route('admin.videos.index')
      ->with('success', '✅ Video berhasil dihapus!');
  }

  public function deleteThumbnail(Video $video)
  {
    if ($video->thumbnail) {
      Storage::disk('public')->delete($video->thumbnail);
      $video->update(['thumbnail' => null]);
    }

    return redirect()->back()
      ->with('success', '✅ Thumbnail berhasil dihapus!');
  }

  /**
   * Generate unique slug
   */
  private function generateUniqueSlug($title, $excludeId = null): string
  {
    $slug = Str::slug($title);
    $originalSlug = $slug;
    $count = 1;

    // Check if slug exists (excluding current video)
    $query = Video::where('slug', $slug);
    if ($excludeId) {
      $query->where('id', '!=', $excludeId);
    }

    while ($query->exists()) {
      $slug = $originalSlug . '-' . Str::random(6);
      $count++;

      // Prevent infinite loop
      if ($count > 10) {
        $slug = $originalSlug . '-' . time();
        break;
      }

      // Reset query for next check
      $query = Video::where('slug', $slug);
      if ($excludeId) {
        $query->where('id', '!=', $excludeId);
      }
    }

    return $slug;
  }
}