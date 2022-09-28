<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Lenny4\DoctrineMergePersistentCollectionBundle\Tests\Entity\Father;

/**
 * @extends ServiceEntityRepository<Father>
 *
 * @method Father|null find($id, $lockMode = null, $lockVersion = null)
 * @method Father|null findOneBy(array $criteria, array $orderBy = null)
 * @method Father[]    findAll()
 * @method Father[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Father::class);
    }

    public function add(Father $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Father $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
