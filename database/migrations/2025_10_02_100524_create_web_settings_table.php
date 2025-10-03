<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, email, etc
            $table->timestamps();
        });

        DB::table('web_settings')->insert([
            ['key' => 'site_name', 'value' => 'Admin Dashboard', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'Modern Admin Dashboard', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_email', 'value' => 'admin@example.com', 'type' => 'email', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_phone', 'value' => '+62 123 4567 890', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('web_settings');
    }
};