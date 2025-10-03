<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoLikeController extends Controller
{

  public function like(Request $request, Video $video)
  {
    $userId = Auth::user()->id;
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