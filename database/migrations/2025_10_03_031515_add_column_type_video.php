<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('videos', function (Blueprint $table) {
      $table->enum('type', ['url', 'iframe'])->default('url')->after('description');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('videos', function (Blueprint $table) {
      $table->dropColumn('type');
    });
  }
};