<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
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
     * - `purchaser_id` : 購入者ID
     * - `status_id` : 発送状況ステータス
     * - `ship_address` : 配送先住所
     * - `payment_id` : 決済ID
     * - `email` : 購入者のメールアドレス
     *
     * @var array
     */
    protected $fillable = ['item_id', 'purchaser_id', 'status_id', 'ship_address', 'payment_id', 'email'];

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
        return $this->belongsTo(User::class, 'Purchaser_id');
    }

    /**
     * このモデルが所属する発送状況を取得する。
     *
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するStatusモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * このモデルが所属する決済を取得する。
     *
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するPaymentモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
