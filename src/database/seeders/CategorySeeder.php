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
        // メインカテゴリーの作成
        $fashionId = Category::create(['name' => 'ファッション'])->id;
        $gadgetId = Category::create(['name' => 'ガジェット'])->id;
        $outdoorId = Category::create(['name' => 'アウトドア'])->id;
        $sportsId = Category::create(['name' => 'スポーツ'])->id;
        $hobbyId = Category::create(['name' => '趣味'])->id;

        // ファッションのサブカテゴリー
        $jacketId = Category::create(['name' => 'ジャケット', 'parent_id' => $fashionId])->id;
        Category::create(['name' => 'ミリタリージャケット', 'parent_id' => $jacketId]);
        Category::create(['name' => 'フライトジャケット', 'parent_id' => $jacketId]);
        Category::create(['name' => 'ダウンジャケット', 'parent_id' => $jacketId]);

        $shirtId = Category::create(['name' => 'シャツ', 'parent_id' => $fashionId])->id;
        Category::create(['name' => 'Tシャツ', 'parent_id' => $shirtId]);
        Category::create(['name' => 'ワイシャツ', 'parent_id' => $shirtId]);

        $pantsId = Category::create(['name' => 'パンツ', 'parent_id' => $fashionId])->id;
        Category::create(['name' => 'ジーンズ', 'parent_id' => $pantsId]);
        Category::create(['name' => 'スラックス', 'parent_id' => $pantsId]);
        Category::create(['name' => 'チノパン', 'parent_id' => $pantsId]);

        $shoesId = Category::create(['name' => '靴', 'parent_id' => $fashionId])->id;
        Category::create(['name' => 'フォーマルシューズ', 'parent_id' => $shoesId]);
        Category::create(['name' => 'カジュアルシューズ', 'parent_id' => $shoesId]);
        Category::create(['name' => 'ブーツ', 'parent_id' => $shoesId]);
        Category::create(['name' => 'サンダル', 'parent_id' => $shoesId]);

        // ガジェットのサブカテゴリー
        $smartphoneId = Category::create(['name' => 'スマートフォン', 'parent_id' => $gadgetId])->id;
        Category::create(['name' => 'Android', 'parent_id' => $smartphoneId]);
        Category::create(['name' => 'Apple', 'parent_id' => $smartphoneId]);

        $pcId = Category::create(['name' => 'パソコン', 'parent_id' => $gadgetId])->id;
        $desktopId = Category::create(['name' => 'デスクトップ', 'parent_id' => $pcId])->id;
        Category::create(['name' => 'タワーケース', 'parent_id' => $desktopId]);
        Category::create(['name' => 'ミッドタワーケース', 'parent_id' => $desktopId]);
        Category::create(['name' => 'ミニタワーケース', 'parent_id' => $desktopId]);

        $notebookId = Category::create(['name' => 'ノートブック', 'parent_id' => $pcId])->id;
        Category::create(['name' => 'クラムシェル ノートパソコン', 'parent_id' => $notebookId]);
        Category::create(['name' => '2 in 1 ノートパソコン', 'parent_id' => $notebookId]);

        // アウトドアのサブカテゴリー
        $campId = Category::create(['name' => 'キャンプ', 'parent_id' => $outdoorId])->id;
        Category::create(['name' => 'テント', 'parent_id' => $campId]);
        Category::create(['name' => 'ランダン', 'parent_id' => $campId]);
        Category::create(['name' => 'アウトドアウェア', 'parent_id' => $campId]);

        $hikingId = Category::create(['name' => 'ハイキング', 'parent_id' => $outdoorId])->id;
        Category::create(['name' => 'バックパック', 'parent_id' => $hikingId]);
        Category::create(['name' => 'トレッキングポール', 'parent_id' => $hikingId]);

        $fishingId = Category::create(['name' => '釣り', 'parent_id' => $outdoorId])->id;
        Category::create(['name' => '竿', 'parent_id' => $fishingId]);
        Category::create(['name' => 'リール', 'parent_id' => $fishingId]);
        Category::create(['name' => 'クーラボックス', 'parent_id' => $fishingId]);
        $lureId = Category::create(['name' => 'ルアー', 'parent_id' => $fishingId])->id;
        Category::create(['name' => 'ミノー', 'parent_id' => $lureId]);
        Category::create(['name' => 'クランクベイト', 'parent_id' => $lureId]);
        Category::create(['name' => 'ワーム', 'parent_id' => $lureId]);

        // スポーツのサブカテゴリー
        $soccerId = Category::create(['name' => 'サッカー', 'parent_id' => $sportsId])->id;
        $futsalId = Category::create(['name' => 'フットサル', 'parent_id' => $soccerId])->id;
        Category::create(['name' => 'サッカーボール', 'parent_id' => $futsalId]);
        Category::create(['name' => 'シューズ', 'parent_id' => $futsalId]);
        // フットサルとフットボールでカテゴリーは同じなので、繰り返さずに示します

        $basketballId = Category::create(['name' => 'バスケット', 'parent_id' => $sportsId])->id;
        Category::create(['name' => 'バスケットボール', 'parent_id' => $basketballId]);

        $badmintonId = Category::create(['name' => 'バドミントン', 'parent_id' => $sportsId])->id;
        Category::create(['name' => 'ラケット', 'parent_id' => $badmintonId]);
        Category::create(['name' => 'シャトル', 'parent_id' => $badmintonId]);

        // 趣味のサブカテゴリー
        $modelId = Category::create(['name' => '模型', 'parent_id' => $hobbyId])->id;
        $plasticModelId = Category::create(['name' => 'プラスチックモデル', 'parent_id' => $modelId])->id;
        Category::create(['name' => '飛行機', 'parent_id' => $plasticModelId]);
        Category::create(['name' => '車', 'parent_id' => $plasticModelId]);
        Category::create(['name' => '船', 'parent_id' => $plasticModelId]);
        Category::create(['name' => '軍用車両', 'parent_id' => $plasticModelId]);

        $woodModelId = Category::create(['name' => '木製モデル', 'parent_id' => $modelId])->id;
        Category::create(['name' => '船', 'parent_id' => $woodModelId]);
        Category::create(['name' => '車', 'parent_id' => $woodModelId]);
        Category::create(['name' => '飛行機', 'parent_id' => $woodModelId]);

        $metalModelId = Category::create(['name' => '金属製モデル', 'parent_id' => $modelId])->id;
        Category::create(['name' => '車', 'parent_id' => $metalModelId]);
        Category::create(['name' => 'トラック', 'parent_id' => $metalModelId]);
        Category::create(['name' => '飛行機', 'parent_id' => $metalModelId]);

        $gameId = Category::create(['name' => 'ゲーム', 'parent_id' => $hobbyId])->id;
        Category::create(['name' => 'カード', 'parent_id' => $gameId]);
        Category::create(['name' => 'レトロゲーム', 'parent_id' => $gameId]);
    }
}
