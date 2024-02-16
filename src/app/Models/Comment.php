<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // --------------------------------------------------------------------------------
    // モデル属性とリレーションシップ
    // --------------------------------------------------------------------------------

    /**
     * マスアサインメントで使用可能な属性。
     *
     * この属性リストを通じて、createやupdateメソッドなどで一括して割り当て可能なモデルの属性を定義する。
     * - `item_id` : 商品ID
     * - `user_id` : 投稿者ID
     * - `message` : コメントメッセージ
     *
     * @var array
     */
    protected $fillable = ['item_id', 'user_id', 'message'];

    /**
     * このモデルが所属する商品を取得する。
     *
     * このメソッドは1対1のリレーションシップの逆向きを表し、所属するItemモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * このモデルが所属する出品者を取得する。
     *
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するUserモデルのインスタンスを返す。
     * 購入明細は `Purchaser_id` カラムを介してユーザーと関連付けられる。
     * なお、ここでのユーザーは、商品を購入しているユーザーである。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
