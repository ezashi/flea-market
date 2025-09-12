<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
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

    /**
     * 取引中の商品を取得（各ユーザーの評価状況に基づいて個別に判定）
     */
    public function tradingItems()
    {
        $userId = $this->id;

        $items = Item::where(function($query) use ($userId) {
            $query->where('seller_id', $userId)->orWhere('buyer_id', $userId);
        })
        ->where('sold', true)
        ->with([
            'chatMessages' => function($query) {
                $query->where('is_deleted', false)
                ->orderBy('created_at', 'desc')
                ->limit(1);
            }, 'buyer', 'seller', 'evaluations' => function($query) {
                $query->select('item_id', 'evaluator_id', 'evaluated_id');
            }
        ])
        ->get();

        $tradingItems = $items->filter(function($item) use ($userId) {
            if (!$item->is_transaction_completed) {
                return true;
            }

            $myEvaluation = $item->evaluations->firstWhere('evaluator_id', $userId);

            return $myEvaluation === null;
        });

        return $tradingItems->sortByDesc(function($item) {
            $latestMessage = $item->chatMessages->first();
            if ($latestMessage) {
                return $latestMessage->created_at;
            }
            return $item->updated_at;
        })->values();
    }

    /**
     * 指定商品の未読メッセージ数を取得
     */
    public function getUnreadMessagesCount($itemId)
    {
        return ChatMessage::where('item_id', $itemId)
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->where('is_deleted', false)
            ->count();
    }

    /**
     * 自分が関わる取引中のアイテムの未読メッセージ数を全て取得
     */
    public function getAllUnreadCounts()
    {
        $userId = $this->id;

        $itemIds = $this->tradingItems()->pluck('id');

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

    // 受けた評価
    public function receivedEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluated_id');
    }

    // 送った評価
    public function sentEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    // 平均評価を取得
    public function getAverageRating()
    {
        $evaluations = $this->receivedEvaluations();
        $count = $evaluations->count();

        if ($count === 0) {
            return 0;
        }

        $average = $evaluations->avg('rating');
        return round($average);
    }

    // 評価数の取得
    public function getEvaluationCount()
    {
        return $this->receivedEvaluations()->count();
    }
}