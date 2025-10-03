<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
  public function index()
  {
    $categories = Category::withCount('videos')->ordered()->paginate(15);
    return view('admin.categories.index', compact('categories'));
  }

  public function create()
  {
    return view('admin.categories.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:categories,name',
      'description' => 'nullable|string',
      'icon_svg' => 'nullable|string',
      'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
      'order' => 'required|integer|min:0',
      'is_active' => 'boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    Category::create($validated);

    return redirect()->route('admin.categories.index')
      ->with('success', '✅ Kategori berhasil ditambahkan!');
  }

  public function edit(Category $category)
  {
    return view('admin.categories.edit', compact('category'));
  }

  public function update(Request $request, Category $category)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
      'description' => 'nullable|string',
      'icon_svg' => 'nullable|string',
      'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
      'order' => 'required|integer|min:0',
      'is_active' => 'boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $category->update($validated);

    return redirect()->route('admin.categories.index')
      ->with('success', '✅ Kategori berhasil diperbarui!');
  }

  public function destroy(Category $category)
  {
    // Check if category has videos
    if ($category->videos()->count() > 0) {
      return redirect()->back()
        ->with('error', '❌ Tidak bisa menghapus kategori yang memiliki video!');
    }

    $category->delete();

    return redirect()->route('admin.categories.index')
      ->with('success', '✅ Kategori berhasil dihapus!');
  }

  public function toggleStatus(Category $category)
  {
    $category->update(['is_active' => !$category->is_active]);

    $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
    return redirect()->back()
      ->with('success', "✅ Kategori berhasil {$status}!");
  }
}
