<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $categories = Category::active()->ordered()->get();

    // Get selected category from query
    $selectedCategory = $request->input('category');

    // Query videos
    $videosQuery = Video::published()
      ->with(['category', 'user'])
      ->when($selectedCategory, function ($q) use ($selectedCategory) {
        return $q->where('category_id', $selectedCategory);
      });

    // Main videos (regular)
    $videos = (clone $videosQuery)->latest('published_at')->paginate(12);

    // Short videos (duration <= 60 seconds)
    $shortVideos = Video::published()
      ->with(['category', 'user'])
      ->where('duration', '<=', 1000)
      ->latest('published_at')
      ->limit(10)
      ->get();

    // $shortVideos = Video::published()
    //   ->with(['category', 'user'])
    //   ->where('views', '>', 0)
    //   ->latest('published_at')
    //   ->limit(10)
    //   ->get();

    return view('frontend.home', compact('categories', 'videos', 'shortVideos', 'selectedCategory'));
  }

  public function show($slug)
  {
    $video = Video::where('slug', $slug)
      ->with(['category', 'user'])
      ->firstOrFail();

    // Increment views
    $video->incrementViews();

    // Related videos (same category)
    $relatedVideos = Video::published()
      ->where('category_id', $video->category_id)
      ->where('id', '!=', $video->id)
      ->limit(10)
      ->get();

    return view('frontend.watch', compact('video', 'relatedVideos'));
  }

  public function search(Request $request)
  {
    $query = $request->input('q');
    $categories = Category::active()->ordered()->get();

    $videos = Video::published()
      ->with(['category', 'user'])
      ->when($query, function ($q) use ($query) {
        return $q->where('title', 'like', '%' . $query . '%')
          ->orWhere('description', 'like', '%' . $query . '%')
          ->orWhereJsonContains('tags', $query);
      })
      ->latest('published_at')
      ->paginate(16);

    $shortVideos = collect(); // Empty for search page
    $selectedCategory = null;

    return view('frontend.home', compact('categories', 'videos', 'shortVideos', 'selectedCategory', 'query'));
  }

  public function like(Request $request, $slug)
  {
    $video = Video::where('slug', $slug)->firstOrFail();
    $userId = Auth::user() ? Auth::user()->id : null;
    $ipAddress = $request->ip();

    // Check if already liked
    $query = DB::table('video_likes')->where('video_id', $video->id);

    if ($userId) {
      $query->where('user_id', $userId);
    } else {
      $query->where('ip_address', $ipAddress);
    }

    $existingLike = $query->first();

    if ($existingLike) {
      // Unlike
      $query->delete();
      $video->decrement('likes');
      $liked = false;
    } else {
      // Like
      DB::table('video_likes')->insert([
        'video_id' => $video->id,
        'user_id' => $userId,
        'ip_address' => $ipAddress,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      $video->increment('likes');
      $liked = true;
    }

    return response()->json([
      'success' => true,
      'liked' => $liked,
      'likes' => $video->fresh()->likes
    ]);
  }
}