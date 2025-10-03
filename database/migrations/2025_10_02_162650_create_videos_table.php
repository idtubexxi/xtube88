<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('videos', function (Blueprint $table) {
      $table->id();
      $table->foreignId('category_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Uploader
      $table->string('title');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->string('cloudinary_public_id'); // Cloudinary video ID
      $table->string('cloudinary_url'); // Full video URL from Cloudinary
      $table->string('thumbnail')->nullable(); // Local thumbnail path
      $table->integer('duration')->default(0); // In seconds
      $table->integer('views')->default(0);
      $table->integer('likes')->default(0);
      $table->enum('status', ['draft', 'published', 'private'])->default('draft');
      $table->json('tags')->nullable(); // Array of tags
      $table->timestamp('published_at')->nullable();
      $table->timestamps();

      // Indexes for better performance
      $table->index('category_id');
      $table->index('status');
      $table->index('published_at');
      $table->index('views');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('videos');
  }
};
