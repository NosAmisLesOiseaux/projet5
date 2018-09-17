<?php

namespace App\Tests\EntitiesTests;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Capture;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private $comment;
    private $user;
    private $capture;

    public function setup()
    {
        $this->user = new User();
        $this->capture = new Capture();

        $this->comment = new Comment();
        $this->comment->setAuthor($this->user);
        $this->comment->setContent('Commentaire 1');
        $this->comment->setCapture($this->capture);
    }

    public function testCommentIsInstanceOfComment()
    {
        $this->assertInstanceOf(Comment::class, $this->comment);
    }

    public function testCommentHasAuthor()
    {
        $this->assertNotNull($this->comment->getAuthor(), "Le commentaire n'a pas d'auteur.");
    }

    public function testCommentHasContent()
    {
        $this->assertNotNull($this->comment->getContent(), "Le commentaire n'a pas de contenu.");
    }

    public function testCommentHasCapture()
    {
        $this->assertNotNull($this->comment->getCapture(), "Le commentaire n'est pas lié à une observation.");
    }

    public function testCommentIsPublished()
    {
        $this->assertTrue($this->comment->getPublished(), "Le commentaire n'est pas publié.");
    }
}