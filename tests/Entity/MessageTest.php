<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 16:01
 */

namespace App\Tests\EntitiesTests;


use App\Entity\Message;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class MessageTest extends TestCase
{
    private $message;

    public function setup()
    {
        $this->message = new Message();
        $this->message->setLastname("Doe");
        $this->message->setFirstname("John");
        $this->message->setEmail("john.doe@nao.fr");
        $this->message->setMessage("Message");
        $this->message->setSentAt(new \DateTime('now'));
        $this->message->setSubject("Subject");
    }

    public function testIsCreateMessageInstanceOfMessage()
    {
        $this->assertInstanceOf(Message::class, $this->message);
    }

    public function testMessageHasLastname()
    {
        $this->assertNotNull($this->message->getLastname());
    }

    public function testMessageHasFirstname()
    {
        $this->assertNotNull($this->message->getFirstname());
    }

    public function testMessageHasValidEmail()
    {
        $this->assertRegExp("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $this->message->getEmail());
    }

    public function testMessageHasSubject()
    {
        $this->assertNotNull($this->message->getSubject());
    }

    public function testMessageHasMessage()
    {
        $this->assertNotNull($this->message->getMessage());
    }

    public function testMessageHasSentDate()
    {
        $this->assertNotNull($this->message->getSentAt());
    }
}
