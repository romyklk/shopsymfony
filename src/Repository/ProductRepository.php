<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findWithSearch($search)
    {
        $query = $this->createQueryBuilder('p');



        if ($search->getPriceMin()) {
            $query = $query->andWhere('p.price >= ' . $search->getPriceMin() * 100);
        }

        if ($search->getPriceMax()) {
            $query = $query->andWhere('p.price <= ' . $search->getPriceMax() * 100);
        }

        if ($search->getTags()) {
            $query = $query->andWhere('p.tags LIKE :tags')
                ->setParameter('tags', '%' . $search->getTags() . '%');
        }

        if ($search->getCategories()) {
            $query = $query->join('p.category', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

       // dd($query->getQuery()->getResult());

        return $query->getQuery()->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
