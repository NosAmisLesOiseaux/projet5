<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 05/09/18
 * Time: 18:19
 */

namespace App\Tests\Repository;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BirdRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }
}