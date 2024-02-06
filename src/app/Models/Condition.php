<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    /**
     * 商品状態に関連する商品を取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するItemモデルのインスタンスのコレクションを返す。
     * 商品状態は `items` テーブルを介して商品情報と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
