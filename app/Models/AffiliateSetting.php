<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateSetting extends Model
{
  protected $fillable = [
    'key',
    'value',
    'is_enabled',
  ];

  protected $casts = [
    'is_enabled' => 'boolean',
  ];

  /**
   * Get setting value by key
   */
  public static function get($key, $default = null)
  {
    $setting = self::where('key', $key)->first();

    if (!$setting) {
      return $default;
    }

    // Return null if not enabled
    if (!$setting->is_enabled) {
      return null;
    }

    return $setting->value ?? $default;
  }

  /**
   * Set setting value by key
   */
  public static function set($key, $value, $isEnabled = null)
  {
    $data = ['value' => $value];

    if ($isEnabled !== null) {
      $data['is_enabled'] = $isEnabled;
    }

    return self::updateOrCreate(
      ['key' => $key],
      $data
    );
  }

  /**
   * Check if setting is enabled
   */
  public static function isEnabled($key)
  {
    $setting = self::where('key', $key)->first();
    return $setting ? $setting->is_enabled : false;
  }

  /**
   * Toggle setting enabled status
   */
  public static function toggle($key)
  {
    $setting = self::where('key', $key)->first();

    if ($setting) {
      $setting->update(['is_enabled' => !$setting->is_enabled]);
      return $setting->is_enabled;
    }

    return false;
  }

  /**
   * Get all enabled settings
   */
  public static function getAllEnabled()
  {
    return self::where('is_enabled', true)->pluck('value', 'key');
  }
}
