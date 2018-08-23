<?php

// tests/FormTest/Comment/CommentTypeTest.php

namespace App\Tests\FormTest\Comment;

use App\Form\CommentType;
use App\Entity\Comment;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
    	  $formData = array(
            'content' => 'test',
        );

        $commentToCompare = new Comment();

        $form = $this->factory->create(CommentType::class, $commentToCompare);

        $comment = new Comment();
        $comment->setContent('test');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($comment->getContent(), $commentToCompare->getContent());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}