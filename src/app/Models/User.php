<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // --------------------------------------------------------------------------------
    // モデル属性とリレーションシップ
    // --------------------------------------------------------------------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ユーザーに関連する役割のコレクションを取得する。
     *
     * このメソッドは多対多のリレーションシップを表し、関連するRoleモデルのインスタンスのコレクションを返す。
     * ユーザーと役割は `role_user` 中間テーブルを介して関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * ユーザーに関連する詳細情報を取得する。
     *
     * このメソッドは一対一のリレーションシップを表し、関連するUserDetailモデルのインスタンスを返す。
     * ユーザは `user_details` テーブルを介してユーザ詳細情報と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * ユーザーに関連するアイコン画像を取得する。
     *
     * このメソッドは一対一のリレーションシップを表し、関連するUserImageモデルのインスタンスを返す。
     * ユーザは `user_images` テーブルを介してアイコン画像と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userImage()
    {
        return $this->hasOne(UserImage::class);
    }

    /**
     * ユーザーに関連する出品された商品を取得する。
     *
     * このメソッドは一対多のリレーションシップを表し、関連するItemモデルのインスタンスのコレクションを返す。
     * ユーザは `items` テーブルを介して商品情報と関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * ユーザーに関連するお気に入り登録された商品のコレクションを取得する。
     *
     * このメソッドは多対多のリレーションシップを表し、関連するitemモデルのインスタンスのコレクションを返す。
     * ユーザーと商品は `item_user` 中間テーブルを介して関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoritedItems()
    {
        return $this->belongsToMany(Item::class, 'item_user');
    }
}
