<?php

namespace App\Repository;

use App\Entity\Bird;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bird|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bird|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bird[]    findAll()
 * @method Bird[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BirdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bird::class);
    }

//    /**
//     * @return Bird[] Returns an array of Bird objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bird
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $letter
     * @param $elementsPerPage
     * @param $firstEntrance
     * @return mixed
     */
    public function getBirdsByFirstLetter($letter, $elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('b')
            ->where('b.vernacularname LIKE \''.$letter.'%\'')
            ->orderBy('b.vernacularname', 'ASC')
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    /**
     * @param $elementsPerPage
     * @param $firstEntrance
     * @return mixed
     */
    public function getBirdsPerPage($elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.vernacularname', 'ASC')
            ->addOrderBy('b.validname', 'ASC')
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    /**
     * @return mixed
     */
    public function getBirdsByOrderAsc()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.vernacularname', 'ASC')
            ->addOrderBy('b.validname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBirds()
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(b.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $letter
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBirdsByLetter($letter)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(b.id)');
        $qb->where('b.vernacularname LIKE \''.$letter.'%\'');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $region
     * @param $draftStatus
     * @param $waitingStatus
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countSearchBirdsByRegion($region, $draftStatus, $waitingStatus)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(DISTINCT b.id)');
        $qb->join('b.captures', 'c');
        $qb->where('c.region = :region');
        $qb->setParameter('region', $region);
        $qb->andWhere('c.status != :status1');
        $qb->setParameter('status1', $draftStatus);
        $qb->andWhere('c.status != :status2');
        $qb->setParameter('status2', $waitingStatus);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $region
     * @param $draftStatus
     * @param $waitingStatus
     * @param $date
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countSearchBirdsByRegionAndDate($region, $draftStatus, $waitingStatus, $date)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(DISTINCT b.id)');
        $qb->join('b.captures', 'c');
        $qb->where('c.region = :region');
        $qb->setParameter('region', $region);
        $qb->andWhere('c.status != :status1');
        $qb->setParameter('status1', $draftStatus);
        $qb->andWhere('c.status != :status2');
        $qb->setParameter('status2', $waitingStatus);
        $qb->andWhere('c.created_date LIKE \''.$date.'%\'');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $region
     * @param $elementsPerPage
     * @param $firstEntrance
     * @param $draftStatus
     * @param $waitingStatus
     * @return mixed
     */
    public function searchBirdsByRegionPerPage($region, $elementsPerPage, $firstEntrance, $draftStatus, $waitingStatus)
    {
        return $this->createQueryBuilder('b')
            ->join('b.captures', 'c')
            ->andWhere('c.region = :region')
            ->setParameter('region', $region)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', $draftStatus)
            ->andWhere('c.status != :status2')
            ->setParameter('status2', $waitingStatus)
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    /**
     * @param $region
     * @param $draftStatus
     * @param $waitingStatus
     * @param $date
     * @return mixed
     */
    public function searchBirdsByRegionAndDate($region, $draftStatus, $waitingStatus, $date)
    {
        return $this->createQueryBuilder('b')
            ->join('b.captures', 'c')
            ->andWhere('c.region = :region')
            ->setParameter('region', $region)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', $draftStatus)
            ->andWhere('c.status != :status2')
            ->setParameter('status2', $waitingStatus)
            ->andWhere('c.created_date LIKE \''.$date.'%\'')
            ->getQuery()
            ->getResult()
        ;
    }
}
