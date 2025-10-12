<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function paginate(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function status(int $page, int $limit, string $status): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('c')
            ->where('c.status = :status ')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setParameter('status', $status)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Contact
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function search(string $search): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('c.firstName', ':search'),
                    $qb->expr()->like('c.name', ':search')
                )
            )
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Contact[] Returns an array of Contact objects
     */

    public function searchPaginated(string $search, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('c')
            ->where('c.firstName LIKE :search OR c.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function searchStatus(string $search, int $page, int $limit, string $status): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('c')
            ->where('c.firstName LIKE :search OR c.name LIKE :search AND c.status = :status ')
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('status', $status)
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
