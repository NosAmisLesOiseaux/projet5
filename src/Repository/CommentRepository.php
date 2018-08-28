<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

//    /**
//     * @return Comment[] Returns an array of Comment objects
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
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $status
     * @param $elementsPerPage
     * @param $firstEntrance
     * @return mixed
     */
    public function getCommentsByStatusPerPage($status, $elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('c')
            ->where('c.published = :published')
            ->setParameter('published', $status)
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    /**
     * @param $status
     * @param $id
     * @return mixed
     */
    public function getCapturePublishedComments($status, $id)
    {
        return $this->createQueryBuilder('c')
            ->where('c.published = :published')
            ->setParameter('published', $status)
            ->join('c.capture', 'd')
            ->andWhere('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $status
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countPublishedOrReportedComments($status)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->where('c.published = :published');
        $qb->setParameter('published', $status);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $capture
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countCaptureComments($capture)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->where('c.capture = :capture');
        $qb->setParameter('capture', $capture);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $capture
     * @param $status
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countCaptureCommentsByStatus($capture, $status)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->where('c.capture = :capture');
        $qb->setParameter('capture', $capture);
        $qb->andWhere('c.published = :published');
        $qb->setParameter('published', $status);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
