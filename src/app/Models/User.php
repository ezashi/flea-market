<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'postal_code',
        'address',
        'is_profile_completed',
        'building',
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
        'is_profile_completed' => 'boolean',
    ];

    // 出品中の商品
    public function sellingItems()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    // 購入した商品
    public function purchasedItems()
    {
        return $this->hasMany(Item::class, 'buyer_id');
    }

    // 取引中の商品
    public function tradingItems()
    {
        $userId = $this->id;

        return Item::where(function($query) use ($userId) {
            $query->where('seller_id', $userId)->orWhere('buyer_id', $userId);
        })
        ->where('sold', true)
        ->with(['chatMessages' => function($query) {
            $query->where('is_deleted', false)
            ->latest('created_at')
            ->limit(1);
        }, 'buyer', 'seller'])
        ->get()
        ->sortByDesc(function($item) {
            $latestMessage = $item->chatMessages->first();
            return $latestMessage ? $latestMessage->created_at : $item->updated_at;
        })
        ->values(); // インデックスを再構築
    }


    //未読メッセージ数を取得
    public function getUnreadMessagesCount($itemId)
    {
        return ChatMessage::where('item_id', $itemId)
        ->where('sender_id', '!=', $this->id)
        ->where('is_read', false)
        ->where('is_deleted', false)
        ->count();
    }

    // 自分が関わる取引中のアイテムIDを取得
    public function getAllUnreadCounts()
    {
        $userId = $this->id;

        $itemIds = Item::where(function($q) use ($userId) {
            $q->where('seller_id', $userId)->orWhere('buyer_id', $userId);
        })
        ->where('sold', true)
        ->pluck('id');

        if ($itemIds->isEmpty()) {
            return [];
        }

        $unreadCountsQuery = \App\Models\ChatMessage::select('item_id', \DB::raw('COUNT(*) as unread_count'))
        ->whereIn('item_id', $itemIds)
        ->where('sender_id', '!=', $userId)
        ->where('is_read', false)
        ->where('is_deleted', false)
        ->groupBy('item_id')
        ->get();

        return $unreadCountsQuery->pluck('unread_count', 'item_id')->toArray();
    }

    // いいねした商品
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
