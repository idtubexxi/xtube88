<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Video extends Model
{
  protected $fillable = [
    'category_id',
    'user_id',
    'title',
    'slug',
    'description',
    'type',
    'cloudinary_public_id',
    'cloudinary_url',
    'iframe',
    'thumbnail',
    'duration',
    'views',
    'likes',
    'status',
    'tags',
    'published_at',
  ];

  protected $casts = [
    'tags' => 'array',
    'duration' => 'integer',
    'views' => 'integer',
    'likes' => 'integer',
    'published_at' => 'datetime',
  ];

  // Relationships
  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  // Accessors & Mutators
  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;

    // Only generate slug if it's a new record (not updating)
    if (!$this->exists) {
      $this->attributes['slug'] = $this->generateUniqueSlug($value);
    }
  }

  /**
   * Generate unique slug for video
   */
  protected function generateUniqueSlug($title): string
  {
    $slug = Str::slug($title);
    $originalSlug = $slug;
    $count = 1;

    // Check if slug exists and make it unique
    while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
      $slug = $originalSlug . '-' . Str::random(6);
      $count++;

      // Prevent infinite loop
      if ($count > 10) {
        $slug = $originalSlug . '-' . time();
        break;
      }
    }

    return $slug;
  }

  // Scopes
  public function scopePublished($query)
  {
    return $query->where('status', 'published')
      ->whereNotNull('published_at')
      ->where('published_at', '<=', now());
  }

  public function scopePopular($query)
  {
    return $query->orderBy('views', 'desc');
  }

  public function scopeRecent($query)
  {
    return $query->orderBy('published_at', 'desc');
  }

  // Helper methods
  public function getFormattedDurationAttribute()
  {
    $minutes = floor($this->duration / 60);
    $seconds = $this->duration % 60;
    return sprintf('%d:%02d', $minutes, $seconds);
  }

  public function getFormattedViewsAttribute()
  {
    if ($this->views >= 1000000) {
      return round($this->views / 1000000, 1) . 'M';
    } elseif ($this->views >= 1000) {
      return round($this->views / 1000, 1) . 'K';
    }
    return $this->views;
  }

  public function incrementViews()
  {
    $this->increment('views');
  }

  public function getThumbnailUrlAttribute()
  {
    return $this->thumbnail
      ? asset('storage/' . $this->thumbnail)
      : asset('images/default-thumbnail.jpeg');
  }
}