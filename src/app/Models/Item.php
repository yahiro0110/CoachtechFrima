<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するUserモデルのインスタンスを返す。
     * 商品は `seller_id` カラムを介してユーザーと関連付けられる。
     * なお、ここでのユーザーは、商品を出品しているユーザーである。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * このモデルが所属するConditionモデルのインスタンスを取得する。
     *
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するConditionモデルのインスタンスを返す。
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
     * このメソッドは一対多のリレーションシップの逆向きを表し、所属するCategoryモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 商品に関連する商品画像を取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するItemImageモデルのインスタンスのコレクションを返す。
     * 商品は `item_images` テーブルを介して商品画像と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemImages()
    {
        return $this->hasMany(ItemImage::class);
    }

    /**
     * 商品に関連するユーザーのコレクションを取得する。
     *
     * このメソッドは多対多のリレーションシップを表し、関連するUserモデルのインスタンスのコレクションを返す。
     * ユーザーと商品は `item_user` 中間テーブルを介して関連付けられている。
     * なお、ここでのユーザーは、商品をお気に入り登録しているユーザーである。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'item_user');
    }

    // --------------------------------------------------------------------------------
    // クエリスコープとカスタムメソッド
    // --------------------------------------------------------------------------------

    /**
     * ログインしているユーザーとお気に入りで関連付けられた商品にマークを付けるローカルスコープ。
     * 関連した商品があれば、 `user_attached` に `1` を設定し、なければ `0` を設定する。
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Eloquentクエリビルダインスタンス
     * @param int $userId ログインユーザーのID
     * @return \Illuminate\Database\Eloquent\Builder ユーザーに関連付けられているレストランの数を含むクエリ
     */
    public function scopeWithUserAttached($query, $userId)
    {
        return $query->addSelect([
            'user_attached' => DB::table('item_user')
                ->whereColumn('item_user.item_id', 'items.id')
                ->where('user_id', $userId)
                ->selectRaw('count(user_id)')
                ->limit(1)
        ]);
    }

    /**
     * 当該商品に関連するお気に入り登録者の数をカウントするクエリスコープ。
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Eloquentクエリビルダインスタンス
     * @return \Illuminate\Database\Eloquent\Builder 更新されたクエリ
     */
    public function scopeWithFavoriteUserCount($query)
    {
        return $query->addSelect([
            'favoriteUser_count' => DB::table('item_user')
                ->whereColumn('item_user.item_id', 'items.id')
                ->selectRaw('count(*)')
        ]);
    }
}
