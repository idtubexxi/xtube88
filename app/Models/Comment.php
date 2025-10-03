<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comment extends Model
{
  protected $fillable = [
    'video_id',
    'user_id',
    'parent_id',
    'content',
    'likes',
  ];

  protected $casts = [
    'likes' => 'integer',
  ];

  // Relationships
  public function video(): BelongsTo
  {
    return $this->belongsTo(Video::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function parent(): BelongsTo
  {
    return $this->belongsTo(Comment::class, 'parent_id');
  }

  public function replies(): HasMany
  {
    return $this->hasMany(Comment::class, 'parent_id')->with('user')->latest();
  }

  public function likedByUsers(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'comment_likes')->withTimestamps();
  }

  // Scopes
  public function scopeParentOnly($query)
  {
    return $query->whereNull('parent_id');
  }

  public function scopeWithReplies($query)
  {
    return $query->with(['replies.user']);
  }

  // Helper methods
  public function isLikedBy($userId): bool
  {
    if (!$userId) return false;
    return $this->likedByUsers()->where('user_id', $userId)->exists();
  }

  public function getRepliesCountAttribute(): int
  {
    return $this->replies()->count();
  }
}