<?php

namespace shopping\Mail;

use shopping\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * 注文インスタンス
     *
     * @var Cart
     */
    public $cart;

    /**
     * 新しいメッセージインスタンスの生成
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * メッセージの生成
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.orderCreatedEmail');
    }
}
