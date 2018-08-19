<?php

namespace App\Repository;

use App\Entity\Capture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Capture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Capture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Capture[]    findAll()
 * @method Capture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaptureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Capture::class);
    }

//    /**
//     * @return Capture[] Returns an array of Capture objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Capture
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getPublishedCaptures($publishedStatus, $validatedStatus)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status1')
            ->setParameter('status1', $publishedStatus)
            ->orWhere('c.status = :status2')
            ->setParameter('status2', $validatedStatus)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPublishedCapturesPerPage($elementsPerPage, $firstEntrance, $publishedStatus, $validatedStatus)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status1')
            ->setParameter('status1', $publishedStatus)
            ->orWhere('c.status = :status2')
            ->setParameter('status2', $validatedStatus)
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function getLastPublishedCaptures($numberElements, $publishedStatus, $validatedStatus)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status1')
            ->setParameter('status1', $publishedStatus)
            ->orWhere('c.status = :status2')
            ->setParameter('status2',  $validatedStatus)
            ->orderBy('c.created_date', 'desc')
            ->getQuery()
            ->setMaxResults($numberElements)
            ->getResult()
        ;
    }

    public function getPublishedCapture($id, $draftStatus, $waitingStatus)
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', $draftStatus)
            ->andWhere('c.status != :status2')
            ->setParameter('status2', $waitingStatus)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getCapturesByStatus($status)
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCapturesByStatusPerPage($status, $elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function getBirdPublishedCaptures($bird, $draftStatus, $waitingStatus)
    {
        return $this->createQueryBuilder('c')
            ->where('c.bird = :bird')
            ->setParameter('bird', $bird)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', $draftStatus)
            ->andWhere('c.status != :status2')
            ->setParameter('status2', $waitingStatus)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getUserCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $id)
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->setMaxResults($numberOfElementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function countByStatus($status)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->where('t.status = :status');
        $qb->setParameter('status', $status);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countPublishedCaptures($publishedStatus, $validatedStatus)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->andWhere('t.status = :status1');
        $qb->setParameter('status1', $publishedStatus);
        $qb->orWhere('t.status = :status2');
        $qb->setParameter('status2', $validatedStatus);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countByStatusAndAuthor($status, $author)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->where('t.status = :status');
        $qb->setParameter('status', $status);
        $qb->andWhere('t.user = :user');
        $qb->setParameter('user', $author);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countAuthorCaptures($id)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->where('c.user = :user');
        $qb->setParameter('user', $id);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countSearchCapturesByBirdAndRegion($bird, $region, $draftStatus, $waitingStatus)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->where('c.bird = :bird');
        $qb->setParameter('bird', $bird);
        $qb->andWhere('c.region = :region');
        $qb->setParameter('region', $region);
        $qb->andWhere('c.status != :status1');
        $qb->setParameter('status1', $draftStatus);
        $qb->andWhere('c.status != :status2');
        $qb->setParameter('status2', $waitingStatus);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countSearchCapturesByBird($bird, $draftStatus, $waitingStatus)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->where('c.bird = :bird');
        $qb->setParameter('bird', $bird);
        $qb->andWhere('c.status != :status1');
        $qb->setParameter('status1', $draftStatus);
        $qb->andWhere('c.status != :status2');
        $qb->setParameter('status2', $waitingStatus);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countSearchCapturesByRegion($region, $draftStatus, $waitingStatus)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->andWhere('c.region = :region');
        $qb->setParameter('region', $region);
        $qb->andWhere('c.status != :status1');
        $qb->setParameter('status1', $draftStatus);
        $qb->andWhere('c.status != :status2');
        $qb->setParameter('status2', $waitingStatus);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchCapturesByBirdAndRegionPerPage($bird, $region, $numberOfElementsPerPage, $firstEntrance, $draftStatus, $waitingStatus)
    {
        return $this->createQueryBuilder('c')
            ->where('c.bird = :bird')
            ->setParameter('bird', $bird)
            ->andWhere('c.region = :region')
            ->setParameter('region', $region)
            ->andWhere('c.status != :status1')
        	->setParameter('status1', $draftStatus)
        	->andWhere('c.status != :status2')
        	->setParameter('status2', $waitingStatus)
            ->getQuery()
            ->setMaxResults($numberOfElementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function searchCapturesByBirdPerPage($bird, $numberOfElementsPerPage, $firstEntrance, $draftStatus, $waitingStatus)
    {
        return $this->createQueryBuilder('c')
            ->where('c.bird = :bird')
            ->setParameter('bird', $bird)
            ->andWhere('c.status != :status1')
        	->setParameter('status1', $draftStatus)
        	->andWhere('c.status != :status2')
        	->setParameter('status2', $waitingStatus)
            ->getQuery()
            ->setMaxResults($numberOfElementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function searchCapturesByRegionPerPage($region, $numberOfElementsPerPage, $firstEntrance, $draftStatus, $waitingStatus)
    {
        return $this->createQueryBuilder('c')
            ->Where('c.region = :region')
            ->setParameter('region', $region)
            ->andWhere('c.status != :status1')
        	->setParameter('status1', $draftStatus)
        	->andWhere('c.status != :status2')
        	->setParameter('status2', $waitingStatus)
            ->getQuery()
            ->setMaxResults($numberOfElementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }
}
