<?php

// src/Services/NAOManager.php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class NAOManager
{
	protected $em;
 
	public function __construct(EntityManagerInterface $em)
	{
	    $this->em = $em;
	}

	public function addOrModifyEntity($entity)
	{
 		$this->getEm()->persist($entity);
		$this->getEm()->flush();
	}

	public function removeEntity($entity)
	{
		$this->em->remove($entity);
		$this->em->flush();
	}

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }
}