<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  // Store new comment
  public function store(Request $request, Video $video)
  {
    $validated = $request->validate([
      'content' => 'required|string|max:1000',
      'parent_id' => 'nullable|exists:comments,id'
    ]);

    $comment = $video->comments()->create([
      'user_id' => Auth::user()->id,
      'parent_id' => $validated['parent_id'] ?? null,
      'content' => $validated['content'],
    ]);

    $comment->load('user', 'replies');

    return response()->json([
      'success' => true,
      'comment' => $comment,
      'html' => view('components.comment-item', ['comment' => $comment])->render()
    ]);
  }

  // Like/Unlike comment
  public function like(Comment $comment)
  {
    $userId = Auth::user()->id;

    if (!$userId) {
      return response()->json(['error' => 'Login required'], 401);
    }

    $isLiked = $comment->likedByUsers()->where('user_id', $userId)->exists();

    if ($isLiked) {
      // Unlike
      $comment->likedByUsers()->detach($userId);
      $comment->decrement('likes');
      $liked = false;
    } else {
      // Like
      $comment->likedByUsers()->attach($userId);
      $comment->increment('likes');
      $liked = true;
    }

    return response()->json([
      'success' => true,
      'liked' => $liked,
      'likes' => $comment->fresh()->likes
    ]);
  }

  // Delete comment
  public function destroy(Comment $comment)
  {
    // Check authorization
    if (Auth::user()->id !== $comment->user_id && !Auth::user()->isAdmin()) {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    $comment->delete();

    return response()->json([
      'success' => true,
      'message' => 'Comment deleted'
    ]);
  }

  // Update comment
  public function update(Request $request, Comment $comment)
  {
    $user = Auth::user();
    // Check authorization
    if ($user->id !== $comment->user_id) {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'content' => 'required|string|max:1000'
    ]);

    $comment->update($validated);

    return response()->json([
      'success' => true,
      'comment' => $comment
    ]);
  }
}