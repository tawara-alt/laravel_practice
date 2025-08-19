<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder; // 作成したUserSeederを使うために追記

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

         $this->call(UserSeeder::class); // UserSeeder を呼び出す記述を追加

         //User::factory()->create([
         //   'name' => 'Test User',
         //   'email' => 'test@example.com',
       // ]);
    }
}
