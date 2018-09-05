<?php

namespace App\Tests\Entity;


use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private $image;

    public function setup()
    {
        $this->image = new Image();
    }

    public function testImageIsInstanceOfImage()
    {
        $this->assertInstanceOf(Image::class, $this->image, "Image n'est pas une instance d'Image");
    }
}
