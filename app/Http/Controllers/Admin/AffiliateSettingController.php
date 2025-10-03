<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AffiliateHelper;
use App\Http\Controllers\Controller;
use App\Models\AffiliateSetting;
use Illuminate\Http\Request;

class AffiliateSettingController extends Controller
{
  public function index()
  {
    $settings = AffiliateSetting::all()->keyBy('key');
    return view('admin.affiliate.index', compact('settings'));
  }

  public function update(Request $request)
  {
    $validated = $request->validate([
      'header_affiliate_code' => 'nullable|string',
      'offer_link_1' => 'nullable|url',
      'offer_link_2' => 'nullable|url',
      'banner_horizontal_small' => 'nullable|string',
      'banner_horizontal_large' => 'nullable|string',
      'banner_rectangle_small' => 'nullable|string',
      'banner_rectangle_large' => 'nullable|string',
      'banner_native_1' => 'nullable|string',
      'banner_native_2' => 'nullable|string',
      'banner_popup_centered' => 'nullable|string',
      'banner_bottom_sticky' => 'nullable|string',
      'banner_header_sticky' => 'nullable|string',
      'histats_code' => 'nullable|string',
    ]);

    // Update each setting
    foreach ($validated as $key => $value) {
      $isEnabled = $request->has("enable_{$key}");
      AffiliateSetting::set($key, $value, $isEnabled);
    }

    // Clear affiliate cache
    AffiliateHelper::clearCache();

    return redirect()->back()->with('success', '✅ Pengaturan Affiliate & Ads berhasil diperbarui!');
  }

  public function toggle(Request $request, $key)
  {
    $newStatus = AffiliateSetting::toggle($key);

    // Clear affiliate cache
    AffiliateHelper::clearCache();

    $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
    return redirect()->back()->with('success', "✅ Banner berhasil {$status}!");
  }
}