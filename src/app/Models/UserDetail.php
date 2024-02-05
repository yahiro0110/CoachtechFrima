<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'user_details';

    // --------------------------------------------------------------------------------
    // モデル属性とリレーションシップ
    // --------------------------------------------------------------------------------

    /**
     * マスアサインメントで使用可能な属性。
     *
     * この属性リストを通じて、createやupdateメソッドなどで一括して割り当て可能なモデルの属性を定義する。
     * - `postal` : 郵便番号
     * - `address` : 住所
     * - `building` : 建物名
     * - `introduction` : 自己紹介文
     *
     * @var array
     */
    protected $fillable = ['postal', 'address', 'building', 'introduction'];

    /**
     * このモデルが所属するUserモデルのインスタンスを取得する。
     *
     * このメソッドは1対1のリレーションシップの逆向きを表し、所属するUserモデルのインスタンスを返す。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
