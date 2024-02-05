<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの定義
        $initialUsers = [
            [
                'name' => '管理者',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ユーザ',
                'email' => 'user@example.com',
                'password' => Hash::make('user123'),
                'email_verified_at' => now(),
            ]
        ];

        // 各初期データを挿入する
        foreach ($initialUsers as $userData) {

            // ユーザーを作成する
            $user = User::create($userData);

            // ロールデータを取得する
            $roles = Role::all();

            if ($userData['name'] === '管理者') {

                // 管理者には全てのロールを割り当てる
                $user->roles()->attach($roles->pluck('id')->toArray());

                // 管理者の詳細情報を作成する
                $user->userDetail()->create([
                    'postal' => '1234567',
                    'address' => '東京都新宿区新宿1-1-1',
                    'building' => '新宿ビルディング',
                    'introduction' => '管理者です。よろしくお願いします。',
                ]);

                // 管理者の画像を作成する
                $user->userImage()->create();
            } else {

                // ユーザには「ユーザ」のロールを割り当てる
                $user->roles()->attach($roles->find(2));

                // ユーザの詳細情報を作成する
                $user->userDetail()->create();

                // ユーザの画像を作成する
                $user->userImage()->create();
            }
        }
    }
}
