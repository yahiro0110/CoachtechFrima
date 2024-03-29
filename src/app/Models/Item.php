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

    /**
     * 商品の購入有無を示す属性を追加する。
     * - `purchased` : 購入済みの場合は `true`、未購入の場合は `false`
     */
    protected $appends = ['purchased', 'soldout'];

    /**
     * 商品の購入有無を示す属性を取得する。
     * 'Item'モデルのインスタンスを取得する際に、自動的に'purchased'属性が計算され、その値がモデルのインスタンスに含まれる。
     *
     * 補足:メソッド名は一定の命名規則に従う必要がある
     * - get{属性名}Attribute
     * - 例: get{属性名}Attribute
     *
     * @return bool
     */
    public function getPurchasedAttribute()
    {
        return $this->purchases()->exists();
    }

    /**
     * 商品が売却済みかどうかを示す属性を取得する。
     *
     * 'soldout' 属性は、商品が購入され('purchased'がtrue)、さらに購入レコードのstatus_idが4である場合にtrueを返す。
     *
     * @return bool
     */
    public function getSoldoutAttribute()
    {
        // 'purchased'がtrueでなければ、売却済みでないと判断する
        if (!$this->getPurchasedAttribute()) {
            return false;
        }

        // 'purchases'リレーションを利用して、status_idが4のレコードが存在するかチェックする
        return $this->purchases()->where('status_id', 4)->exists();
    }

    /**
     * 商品に関連する購入明細を取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するPurchaseモデルのインスタンスのコレクションを返す。
     * 商品は `purchases` テーブルを介して購入明細と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * 商品に関連するコメントを取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するCommentモデルのインスタンスのコレクションを返す。
     * 商品は `comments` テーブルを介してコメントと関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
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
