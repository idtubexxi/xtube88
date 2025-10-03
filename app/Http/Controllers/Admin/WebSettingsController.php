<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class WebSettingsController extends Controller
{
    public function index()
    {
        $settings = WebSetting::all()->keyBy('key');
        return view('admin.web-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            WebSetting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
