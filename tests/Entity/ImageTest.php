<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 16:33
 */

namespace App\Tests\Entity;


use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private $image;

    public function setup()
    {
        $this->image = new Image();
        $this->image->setExtension('.png');
        $this->image->setSlug('image');
        $this->image->setWeight(15000);
    }

    public function testIsImageInstanceOfImage()
    {
        $this->assertInstanceOf(Image::class, $this->image);
    }

    public function testImageHasValidExtension()
    {
        $extensions = array('.png', '.jpeg', '.jpg', '.gif');
        foreach ($extensions as $key => $value) {
            dump($value);
            //$this->assertEquals($value, $this->image->getExtension(), "L'extension n'est pas valide");
        }
        dump($this->image->getExtension());
    }

    public function testImageHasValidSlug()
    {
        $this->assertNotNull($this->image->getSlug(), "Le slug de l'image est null");
        $this->assertEquals('image',$this->image->getSlug(), "Le slug de l'image est invalide");
    }

    public function testImageIsLight()
    {
        $this->assertLessThanOrEqual(2000000, $this->image->getWeight(), "L'image est trop lourde.");
    }
}
