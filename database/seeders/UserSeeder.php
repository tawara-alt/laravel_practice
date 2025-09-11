<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Userモデルを使うために追記
use Illuminate\Support\Facades\Hash; // パスワードのハッシュ化のために追記

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           // ここにユーザーを作成する処理を書きます

        // デフォルト認証（メールアドレス）でログインできるユーザーを作成
        if (!User::where('email', 'test@example.com')->exists()) {

        User::create(
		        [
		            'name' => 'テストユーザー',
		            'email' => 'test@example.com', // 認証に使うメールアドレスを設定
		            'password' => Hash::make('password'), // パスワードをハッシュ化して保存
		            // 他に必要なデフォルトカラムがあればここに追加 (例: email_verified_at など)
		        ],
		        
        );}
        User::create(
                [
                    'name' => '担当者A',
                    'email' => 'tanto_a@example.com',
                    'password' => Hash::make('password123'),
                ]
        );

        User::create(
                [
                    'name' => '担当者B',
                    'email' => 'tanto_b@example.com',
                    'password' => Hash::make('password123'),
                ]
        );

        User::create(
                [
                    'name' => '担当者C',
                    'email' => 'tanto_c@example.com',
                    'password' => Hash::make('password123'),
                ]
        );

    }
}
