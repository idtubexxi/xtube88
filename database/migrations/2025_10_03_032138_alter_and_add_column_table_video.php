<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('videos', function (Blueprint $table) {
      $table->text('iframe')->nullable()->after('cloudinary_url'); // Embed iframe code
      $table->string('cloudinary_public_id')->nullable()->change();
      $table->string('cloudinary_url')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('videos', function (Blueprint $table) {
      $table->dropColumn('iframe');
      $table->integer('cloudinary_public_id')->nullable(false)->change();
      $table->integer('cloudinary_url')->nullable(false)->change();
    });
  }
};