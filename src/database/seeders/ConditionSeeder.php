<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの定義
        $initialConditionNames = ['新品、未使用', '未使用に近い', '目立った傷や汚れなし', 'やや傷や汚れあり', '傷や汚れあり'];

        // 配列を変換して一括挿入
        $initialConditions = array_map(function ($name) {
            return ['name' => $name];
        }, $initialConditionNames);

        Condition::insert($initialConditions);
    }
}
