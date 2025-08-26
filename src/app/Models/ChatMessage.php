<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'sender_id',
        'message',
        'image_path',
        'message_type',
        'is_read',
    ];

    /**
     * メッセージの送信者
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * メッセージに関連する商品
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * テキストメッセージかどうか
     */
    public function isText()
    {
        return $this->message_type === 'text';
    }

    /**
     * 画像メッセージかどうか
     */
    public function isImage()
    {
        return $this->message_type === 'both' && $this->image_path;
    }

    /**
     * 画像URLを取得
     */
    public function getImageUrl()
    {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }
        return null;
    }
}