<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'evaluator_id',
        'evaluated_id',
        'rating',
    ];

    /**
     * 評価者のリレーション
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * 評価される人のリレーション
     */
    public function evaluated()
    {
        return $this->belongsTo(User::class, 'evaluated_id');
    }

    /**
     * 商品のリレーション
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}