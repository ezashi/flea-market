<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'content',
    ];

    //コメントを投稿したユーザーを取得
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //コメントされたアイテムを取得
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
