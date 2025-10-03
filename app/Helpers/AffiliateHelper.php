<?php

namespace App\Helpers;

use App\Models\AffiliateSetting;
use Illuminate\Support\Facades\Cache;

class AffiliateHelper
{
  /**
   * Get enabled affiliate setting by key
   */
  public static function get(string $key): ?string
  {
    // Cache for 1 hour to reduce database queries
    return Cache::remember("affiliate_{$key}", 3600, function () use ($key) {
      $setting = AffiliateSetting::where('key', $key)->first();

      if ($setting && $setting->is_enabled && $setting->value) {
        return $setting->value;
      }

      return null;
    });
  }

  /**
   * Check if affiliate setting is enabled
   */
  public static function isEnabled(string $key): bool
  {
    return self::get($key) !== null;
  }

  /**
   * Render affiliate code (with proper escaping)
   */
  public static function render(string $key): string
  {
    $code = self::get($key);
    return $code ? $code : '';
  }

  /**
   * Get all enabled affiliate settings
   */
  public static function getAllEnabled(): array
  {
    return Cache::remember('affiliate_all_enabled', 3600, function () {
      return AffiliateSetting::where('is_enabled', true)
        ->whereNotNull('value')
        ->pluck('value', 'key')
        ->toArray();
    });
  }

  /**
   * Clear affiliate cache
   */
  public static function clearCache(): void
  {
    Cache::forget('affiliate_all_enabled');

    $keys = [
      'header_affiliate_code',
      'offer_link_1',
      'offer_link_2',
      'banner_horizontal_small',
      'banner_horizontal_large',
      'banner_rectangle_small',
      'banner_rectangle_large',
      'banner_native_1',
      'banner_native_2',
      'banner_popup_centered',
      'banner_bottom_sticky',
      'banner_header_sticky',
      'histats_code',
    ];

    foreach ($keys as $key) {
      Cache::forget("affiliate_{$key}");
    }
  }
}