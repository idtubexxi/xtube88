<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
  protected $fillable = [
    'name',
    'slug',
    'description',
    'icon_svg',
    'color',
    'order',
    'is_active',
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'order' => 'integer',
  ];

  // Relationships
  public function videos(): HasMany
  {
    // Specify foreign key explicitly to avoid pluralization issue
    return $this->hasMany(Video::class, 'category_id');
  }

  // Accessors & Mutators
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = $value;
    $this->attributes['slug'] = Str::slug($value);
  }

  // Scopes
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeOrdered($query)
  {
    return $query->orderBy('order', 'asc');
  }

  // Helper methods
  public function getVideosCountAttribute()
  {
    return $this->videos()->where('status', 'published')->count();
  }
}
