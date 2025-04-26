<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'condition',
        'image',
        'seller_id',
        'buyer_id',
        'sold',
    ];

    //seller_idから、商品がどのユーザーに出品されたかを取得
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    //buyer_idから、誰が買ったかを取得
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //ログイン中のユーザーがこの商品に「いいね」してるかどうかを判定
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    //この商品が何回「いいね」されてるかを取得
    public function likesCount()
    {
        return $this->likes()->count();
    }
}
