<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの定義
        $initialRoles = [
            [
                'name' => 'admin',
                'description' => 'システム管理者',
            ],
            [
                'name' => 'user',
                'description' => '一般ユーザ',
            ],
        ];

        // 各初期データを挿入する
        foreach ($initialRoles as $roleData) {
            Role::create($roleData);
        }
    }
}
