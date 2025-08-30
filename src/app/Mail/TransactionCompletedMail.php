<?php

namespace App\Mail;

use App\Models\Item;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $seller;
    public $buyer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Item $item, User $seller, User $buyer)
    {
        $this->item = $item;
        $this->seller = $seller;
        $this->buyer = $buyer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('取引の完了をお知らせします。')
            ->view('emails.transaction-completed')
            ->with([
                'item' => $this->item,
                'seller' => $this->seller,
                'buyer' => $this->buyer,
            ]);
    }
}