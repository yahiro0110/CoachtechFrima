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
