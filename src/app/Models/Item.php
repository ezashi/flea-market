<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'condition',
        'name',
        'brand',
        'description',
        'price',
        'seller_id',
        'buyer_id',
        'sold',
        'payment_method'
    ];

    protected $casts = [
        'price' => 'integer',
        'sold' => 'boolean',
    ];

    // 出品者のリレーション
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // 購入者のリレーション
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // カテゴリのリレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // いいねのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメントのリレーション
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // ユーザーがいいねしたかどうかを判定するメソッド
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // チャットメッセージのリレーション
    public function chatMessages()
    {
    return $this->hasMany(ChatMessage::class);
    }
}