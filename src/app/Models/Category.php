<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * カテゴリに関連する商品を取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するItemモデルのインスタンスのコレクションを返す。
     * カテゴリは `items` テーブルを介して商品情報と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
