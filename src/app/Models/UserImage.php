<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'user_images';

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
