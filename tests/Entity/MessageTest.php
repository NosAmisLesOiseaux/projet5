<?php

namespace App\Tests\Entity;


use App\Entity\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    private $message;

    public function setup()
    {
        $this->message = new Message();
    }

    public function testMessageIsInstanceOfMessage()
    {
        $this->assertInstanceOf(Message::class, $this->message, "Message is not instance of Message.");
    }
}
