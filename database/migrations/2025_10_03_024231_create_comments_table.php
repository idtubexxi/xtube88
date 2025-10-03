<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('video_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // For replies
      $table->text('content');
      $table->integer('likes')->default(0);
      $table->timestamps();

      // Indexes
      $table->index('video_id');
      $table->index('user_id');
      $table->index('parent_id');
    });

    Schema::create('comment_likes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('comment_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->timestamps();

      // Unique constraint - user can only like once
      $table->unique(['comment_id', 'user_id']);
    });

    Schema::create('video_likes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('video_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Nullable for guest
      $table->string('ip_address')->nullable(); // For guest tracking
      $table->timestamps();

      // Indexes
      $table->index(['video_id', 'user_id']);
      $table->index(['video_id', 'ip_address']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('comment_likes');
    Schema::dropIfExists('comments');
    Schema::dropIfExists('video_likes');
  }
};