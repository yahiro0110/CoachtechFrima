<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの定義
        $initialCategories = [
            [
                'name' => '服',
            ],
            [
                'name' => '電化製品',
            ],
            [
                'name' => '家具',
            ],
            [
                'name' => '本',
            ],
            [
                'name' => 'その他',
            ],
            [
                'name' => 'メンズ',
                'parent_id' => 1,
            ],
            [
                'name' => 'レディース',
                'parent_id' => 1,
            ],
            [
                'name' => 'キッズ',
                'parent_id' => 1,
            ],
            [
                'name' => 'テレビ',
                'parent_id' => 2,
            ],
            [
                'name' => 'エアコン',
                'parent_id' => 2,
            ],
            [
                'name' => '洗濯機',
                'parent_id' => 2,
            ],
            [
                'name' => '冷蔵庫',
                'parent_id' => 2,
            ],
            [
                'name' => 'ソファ',
                'parent_id' => 3,
            ],
            [
                'name' => 'ベッド',
                'parent_id' => 3,
            ],
            [
                'name' => '机',
                'parent_id' => 3,
            ],
            [
                'name' => '椅子',
                'parent_id' => 3,
            ],
            [
                'name' => '小説',
                'parent_id' => 4,
            ],
            [
                'name' => '漫画',
                'parent_id' => 4,
            ],
            [
                'name' => '専門書',
                'parent_id' => 4,
            ],
            [
                'name' => 'スポーツ用品',
                'parent_id' => 5,
            ],
            [
                'name' => '楽器',
                'parent_id' => 5,
            ],
            [
                'name' => 'ジャケット',
                'parent_id' => 6,
            ],
            [
                'name' => 'シャツ',
                'parent_id' => 6,
            ],
            [
                'name' => 'ワンピース',
                'parent_id' => 7,
            ],
            [
                'name' => 'スカート',
                'parent_id' => 7,
            ],
            [
                'name' => 'トップス',
                'parent_id' => 7,
            ],
            [
                'name' => '体操着',
                'parent_id' => 8,
            ],
            [
                'name' => '液晶モニター',
                'parent_id' => 9,
            ],
            [
                'name' => 'ワンセグ',
                'parent_id' => 9,
            ],
            [
                'name' => '室内用',
                'parent_id' => 10,
            ],
            [
                'name' => 'ポータブル',
                'parent_id' => 10,
            ],
            [
                'name' => 'ドラム式',
                'parent_id' => 11,
            ],
            [
                'name' => '縦型',
                'parent_id' => 11,
            ],
            [
                'name' => '二階層式',
                'parent_id' => 11,
            ],
            [
                'name' => '小型タイプ',
                'parent_id' => 12,
            ],
            [
                'name' => '大型タイプ',
                'parent_id' => 12,
            ],
            [
                'name' => 'シングル',
                'parent_id' => 13,
            ],
            [
                'name' => '二人掛け',
                'parent_id' => 13,
            ],
            [
                'name' => '三人掛け',
                'parent_id' => 13,
            ],
            [
                'name' => 'シングル',
                'parent_id' => 14,
            ],
            [
                'name' => 'ダブル',
                'parent_id' => 14,
            ],
            [
                'name' => '〜90cm',
                'parent_id' => 15,
            ],
            [
                'name' => '90〜120cm',
                'parent_id' => 15,
            ],
            [
                'name' => '120〜180cm',
                'parent_id' => 15,
            ],
            [
                'name' => 'ダイニングチェア',
                'parent_id' => 16,
            ],
            [
                'name' => 'アームチェア',
                'parent_id' => 16,
            ],
            [
                'name' => 'オフィスチェア',
                'parent_id' => 16,
            ],
            [
                'name' => '芥川龍之介',
                'parent_id' => 17,
            ],
            [
                'name' => '太宰治',
                'parent_id' => 17,
            ],
            [
                'name' => '夏目漱石',
                'parent_id' => 17,
            ],
            [
                'name' => 'ジョジョの奇妙な冒険',
                'parent_id' => 18,
            ],
            [
                'name' => 'はじめの一歩',
                'parent_id' => 18,
            ],
            [
                'name' => '賭博黙示録カイジ',
                'parent_id' => 18,
            ],
            [
                'name' => 'NARUTO -ナルト-',
                'parent_id' => 18,
            ],
            [
                'name' => '数学',
                'parent_id' => 19,
            ],
            [
                'name' => '歴史',
                'parent_id' => 19,
            ],
            [
                'name' => '経済',
                'parent_id' => 19,
            ],
            [
                'name' => 'サッカー',
                'parent_id' => 20,
            ],
            [
                'name' => 'バスケットボール',
                'parent_id' => 20,
            ],
            [
                'name' => '野球',
                'parent_id' => 20,
            ],
            [
                'name' => 'テニス',
                'parent_id' => 20,
            ],
            [
                'name' => 'ギター',
                'parent_id' => 21,
            ],
            [
                'name' => 'ベース',
                'parent_id' => 21,
            ],
            [
                'name' => 'ピアノ',
                'parent_id' => 21,
            ],
            [
                'name' => 'ドラム',
                'parent_id' => 21,
            ],
            [
                'name' => 'バット',
                'parent_id' => 60,
            ],
            [
                'name' => 'グローブ',
                'parent_id' => 60,
            ],
            [
                'name' => 'ボール',
                'parent_id' => 60,
            ],
            [
                'name' => 'ラケット',
                'parent_id' => 61,
            ],
        ];

        // 各初期データを挿入する
        foreach ($initialCategories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
