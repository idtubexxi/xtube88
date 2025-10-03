<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandSettingsController extends Controller
{
    public function index()
    {
        $settings = WebSetting::whereIn('key', ['site_logo', 'site_favicon'])->get()->keyBy('key');
        return view('admin.brand-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:1024',
        ]);

        try {
            // Upload Logo
            if ($request->hasFile('logo')) {
                $this->uploadFile($request->file('logo'), 'site_logo', 'logos');
            }

            // Upload Favicon
            if ($request->hasFile('favicon')) {
                $this->uploadFile($request->file('favicon'), 'site_favicon', 'favicons');
            }

            return redirect()->back()->with('success', '✅ Logo dan Favicon berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Gagal upload: ' . $e->getMessage());
        }
    }

    private function uploadFile($file, $settingKey, $folder)
    {
        // Get old file path
        $oldSetting = WebSetting::where('key', $settingKey)->first();

        // Delete old file if exists
        if ($oldSetting && $oldSetting->value) {
            Storage::disk('public')->delete($oldSetting->value);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store new file
        $path = $file->storeAs($folder, $filename, 'public');

        // Update or create setting
        WebSetting::updateOrCreate(
            ['key' => $settingKey],
            ['value' => $path, 'type' => 'image']
        );

        return $path;
    }

    public function deleteLogo()
    {
        try {
            $setting = WebSetting::where('key', 'site_logo')->first();

            if ($setting && $setting->value) {
                Storage::disk('public')->delete($setting->value);
                $setting->value = null;
                $setting->save();
            }

            return redirect()->back()->with('success', '✅ Logo berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Gagal menghapus logo: ' . $e->getMessage());
        }
    }

    public function deleteFavicon()
    {
        try {
            $setting = WebSetting::where('key', 'site_favicon')->first();

            if ($setting && $setting->value) {
                Storage::disk('public')->delete($setting->value);
                $setting->value = null;
                $setting->save();
            }

            return redirect()->back()->with('success', '✅ Favicon berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Gagal menghapus favicon: ' . $e->getMessage());
        }
    }
}