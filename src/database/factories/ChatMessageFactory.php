<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'sender_id' => User::factory(),
            'message' => $this->faker->sentence(),
            'image_path' => null,
            'message_type' => 'text',
            'is_read' => $this->faker->boolean(70),
            'is_edited' => false,
            'is_deleted' => false,
            'edited_at' => null,
            'deleted_at' => null,
        ];
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_read' => false,
            ];
        });
    }

    public function withImage()
    {
        return $this->state(function (array $attributes) {
            return [
                'image_path' => 'storage/images/chat/' . $this->faker->uuid() . '.jpg',
                'message_type' => 'both',
            ];
        });
    }

    public function deleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_deleted' => true,
                'deleted_at' => now(),
                'message' => 'このメッセージは削除されました',
            ];
        });
    }
}