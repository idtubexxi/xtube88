<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    // Share categories with all frontend views
    View::composer('layouts.frontend', function ($view) {
      $categories = Category::active()->ordered()->get();
      $view->with('categories', $categories);
    });
  }
}