<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの定義
        $initialPaymentNames = ['クレジットカード', '代金引換', 'コンビニ', 'ATM', 'ネットバンキング'];

        // 配列を変換して一括挿入
        $initialPayments = array_map(function ($name) {
            return ['name' => $name];
        }, $initialPaymentNames);

        Payment::insert($initialPayments);
    }
}
