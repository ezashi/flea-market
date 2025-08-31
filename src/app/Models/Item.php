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
        'payment_method',
        'is_transaction_completed'
    ];

    protected $casts = [
        'price' => 'integer',
        'sold' => 'boolean',
        'is_transaction_completed' => 'boolean',
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

    // 評価のリレーション
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * 双方の評価が完了しているかチェック
     */
    public function isBothEvaluated()
    {
        return $this->evaluations()->count() >= 2;
    }

    /**
     * 指定ユーザーが評価済みかチェック
     */
    public function isEvaluatedBy($userId)
    {
        return $this->evaluations()
            ->where('evaluator_id', $userId)
            ->exists();
    }

    /**
     * 取引が完全に終了しているかチェック
     * (取引完了 + 双方評価完了)
     */
    public function isFullyCompleted()
    {
        return $this->is_transaction_completed && $this->isBothEvaluated();
    }

    /**
     * 指定ユーザーにとって取引中かどうかを判定
     */
    public function isTradingFor($userId)
    {
        $isParticipant = ($this->seller_id === $userId || $this->buyer_id === $userId);

        if (!$this->sold || !$isParticipant) {
            return false;
        }

        if (!$this->is_transaction_completed) {
            return true;
        }

        return !$this->isEvaluatedBy($userId);
    }

    /**
     * 評価待ちの状態かチェック
     */
    public function isPendingEvaluation($userId)
    {
        // 取引完了済みかつ自分が未評価の場合
        return $this->is_transaction_completed && !$this->isEvaluatedBy($userId) && ($this->seller_id === $userId || $this->buyer_id === $userId);
    }
}