<?php

namespace App\Tests\Entity;


use App\Entity\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private $comment;

    public function setup()
    {
        $this->comment = new Comment();
    }

    public function testCommentIsInstanceOfComment()
    {
        $this->assertInstanceOf(Comment::class, $this->comment, "Comment n'est pas une instance de Comment.");
    }
}
