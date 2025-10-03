<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function clear(Request $request)
    {
        try {
            // Clear all caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');

            // Clear application cache
            Cache::flush();

            return redirect()->back()->with('success', '✅ Semua cache berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    public function optimize(Request $request)
    {
        try {
            // Optimize for production
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return redirect()->back()->with('success', '✅ Aplikasi berhasil dioptimasi!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Gagal mengoptimasi: ' . $e->getMessage());
        }
    }
}
