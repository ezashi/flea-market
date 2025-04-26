<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    //この「いいね」が どのユーザーからされたかを取得
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //この「いいね」が どの商品に対してかを取得
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
