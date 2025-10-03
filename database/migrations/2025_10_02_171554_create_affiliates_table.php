<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('affiliate_settings', function (Blueprint $table) {
      $table->id();
      $table->string('key')->unique();
      $table->text('value')->nullable();
      $table->boolean('is_enabled')->default(false);
      $table->timestamps();
    });

    // Insert default settings
    $settings = [
      // Header Affiliate Code
      ['key' => 'header_affiliate_code', 'value' => null, 'is_enabled' => false],

      // Offer Links
      ['key' => 'offer_link_1', 'value' => null, 'is_enabled' => true],
      ['key' => 'offer_link_2', 'value' => null, 'is_enabled' => true],

      // Banner Ads - Horizontal
      ['key' => 'banner_horizontal_small', 'value' => null, 'is_enabled' => false],
      ['key' => 'banner_horizontal_large', 'value' => null, 'is_enabled' => false],

      // Banner Ads - Rectangle
      ['key' => 'banner_rectangle_small', 'value' => null, 'is_enabled' => false],
      ['key' => 'banner_rectangle_large', 'value' => null, 'is_enabled' => false],

      // Banner Ads - Native
      ['key' => 'banner_native_1', 'value' => null, 'is_enabled' => false],
      ['key' => 'banner_native_2', 'value' => null, 'is_enabled' => false],

      // Special Ads
      ['key' => 'banner_popup_centered', 'value' => null, 'is_enabled' => false],
      ['key' => 'banner_bottom_sticky', 'value' => null, 'is_enabled' => false],
      ['key' => 'banner_header_sticky', 'value' => null, 'is_enabled' => false],

      // Analytics
      ['key' => 'histats_code', 'value' => null, 'is_enabled' => false],
    ];

    foreach ($settings as $setting) {
      DB::table('affiliate_settings')->insert([
        'key' => $setting['key'],
        'value' => $setting['value'],
        'is_enabled' => $setting['is_enabled'],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }

  public function down(): void
  {
    Schema::dropIfExists('affiliate_settings');
  }
};
