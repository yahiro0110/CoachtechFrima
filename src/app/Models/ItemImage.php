<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'item_images';

    /**
     * マスアサインメントで使用可能な属性。
     *
     * この属性リストを通じて、createやupdateメソッドなどで一括して割り当て可能なモデルの属性を定義する。
     * - `item_id` : 商品ID
     * - `image_path` : 画像パス
     *
     * @var array
     */
    protected $fillable = ['item_id', 'image_path'];

    /**
     * このモデルが所属するItemモデルのインスタンスを取得する。
     *
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するItemモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
