<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Lenny4\DoctrineMergePersistentCollectionBundle\Entity\Son;

/**
 * @extends ServiceEntityRepository<Son>
 *
 * @method Son|null find($id, $lockMode = null, $lockVersion = null)
 * @method Son|null findOneBy(array $criteria, array $orderBy = null)
 * @method Son[]    findAll()
 * @method Son[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Son::class);
    }

    public function add(Son $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Son $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
