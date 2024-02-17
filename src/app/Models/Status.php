<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    /**
     * 発送状況に関連する購入明細を取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するPurchaseモデルのインスタンスのコレクションを返す。
     * 発送状況は `purchases` テーブルを介して購入明細と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
