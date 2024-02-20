<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの定義
        $initialStatusNames = ['購入が確定しました', '購入をキャンセルしました', '発送しました', '受取完了しました', '返品しました'];

        // 配列を変換して一括挿入
        $initialStatuses = array_map(function ($name) {
            return ['name' => $name];
        }, $initialStatusNames);

        Status::insert($initialStatuses);
    }
}
