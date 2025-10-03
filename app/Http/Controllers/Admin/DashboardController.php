<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    $stats = [
      'total_categories' => Category::count(),
      'total_videos' => Video::count(),
      'published_videos' => Video::where('status', 'published')->count(),
      'total_views' => Video::sum('views'),
      'total_users' => User::count(),
      'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
    ];

    // Get categories for filter
    $categories = Category::active()->ordered()->get();

    // Get filter parameters
    $categoryId = $request->input('category_id');
    $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
    $endDate = $request->input('end_date', now()->format('Y-m-d'));

    // Videos query with filters
    $videosQuery = Video::query();

    if ($categoryId) {
      $videosQuery->where('category_id', $categoryId);
    }

    if ($startDate && $endDate) {
      $videosQuery->whereBetween('created_at', [
        Carbon::parse($startDate)->startOfDay(),
        Carbon::parse($endDate)->endOfDay()
      ]);
    }

    // Chart data - Videos per day
    $videosPerDay = (clone $videosQuery)
      ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
      ->whereBetween('created_at', [
        Carbon::parse($startDate)->startOfDay(),
        Carbon::parse($endDate)->endOfDay()
      ])
      ->groupBy('date')
      ->orderBy('date', 'asc')
      ->get();

    // Chart data - Views per day
    $viewsPerDay = (clone $videosQuery)
      ->selectRaw('DATE(created_at) as date, SUM(views) as total_views')
      ->whereBetween('created_at', [
        Carbon::parse($startDate)->startOfDay(),
        Carbon::parse($endDate)->endOfDay()
      ])
      ->groupBy('date')
      ->orderBy('date', 'asc')
      ->get();

    // Chart data - Videos by category
    $videosByCategory = Video::select('categories.name', DB::raw('COUNT(*) as count'))
      ->join('categories', 'videos.category_id', '=', 'categories.id')
      ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
        return $q->whereBetween('videos.created_at', [
          Carbon::parse($startDate)->startOfDay(),
          Carbon::parse($endDate)->endOfDay()
        ]);
      })
      ->groupBy('categories.id', 'categories.name')
      ->orderBy('count', 'desc')
      ->limit(10)
      ->get();

    // Chart data - Videos by status
    $videosByStatus = Video::select('status', DB::raw('COUNT(*) as count'))
      ->when($categoryId, function ($q) use ($categoryId) {
        return $q->where('category_id', $categoryId);
      })
      ->groupBy('status')
      ->get();

    // Top videos
    $topVideos = (clone $videosQuery)
      ->with('category')
      ->orderBy('views', 'desc')
      ->limit(5)
      ->get();

    return view('admin.dashboard', compact(
      'stats',
      'categories',
      'categoryId',
      'startDate',
      'endDate',
      'videosPerDay',
      'viewsPerDay',
      'videosByCategory',
      'videosByStatus',
      'topVideos'
    ));
  }
}