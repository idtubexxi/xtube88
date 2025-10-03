<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::create([
      'name' => 'Admin',
      'email' => 'premiumwatchdevice@gmail.com',
      'password' => Hash::make('Padaherang@321'),
      'role' => 'admin',
      'email_verified_at' => now(),
    ]);
    User::create([
      'name' => 'John Doe',
      'email' => 'santisimilikiti93@gmail.com',
      'password' => Hash::make('Amanah@2025'),
      'role' => 'user',
      'email_verified_at' => now(),
    ]);
    $this->command->info('✅ Admin and User created successfully!');
    $this->command->info('📧 Admin: premiumwatchdevice@gmail.com / Padaherang@321');
    $this->command->info('📧 User: santisimilikiti93@gmail.com / Amanah@2025');
  }
}