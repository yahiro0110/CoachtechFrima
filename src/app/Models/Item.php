<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // --------------------------------------------------------------------------------
    // モデル属性とリレーションシップ
    // --------------------------------------------------------------------------------

    /**
     * マスアサインメントで使用可能な属性。
     *
     * この属性リストを通じて、createやupdateメソッドなどで一括して割り当て可能なモデルの属性を定義する。
     * - `name` : 商品名
     * - `description` : 商品説明
     * - `condition_id` : 商品の状態
     * - `price` : 価格
     * - `brand` : ブランド
     * - `seller_id` : 出品者
     * - `category_id` : カテゴリ
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'condition_id', 'price', 'brand', 'seller_id', 'category_id'];

    /**
     * このモデルが所属するUserモデルのインスタンスを取得する。
     *
     * このメソッドは1対1のリレーションシップの逆向きを表し、所属するUserモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このモデルが所属するConditionモデルのインスタンスを取得する。
     *
     * このメソッドは1対1のリレーションシップの逆向きを表し、所属するConditionモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * このモデルが所属するCategoryモデルのインスタンスを取得する。
     *
     * このメソッドは1対1のリレーションシップの逆向きを表し、所属するCategoryモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
