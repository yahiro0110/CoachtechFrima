<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * 役割に関連するユーザのコレクションを取得する。
     *
     * このメソッドは多対多のリレーションシップを表し、関連するUserモデルのインスタンスのコレクションを返す。
     * ユーザーと役割は `role_user` 中間テーブルを介して関連付けられる。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
