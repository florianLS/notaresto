<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function save(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Return the 10 last restaurants created on the website. (Youngest)
    public function findTenLastRegisterRestaurants()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findTenBestRatingRestaurants()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT review.restaurant_id, r.name, r.description, avg(review.rating) as rating
            FROM restaurant as r INNER JOIN review ON r.id = review.restaurant_id 
            GROUP BY review.restaurant_id 
            ORDER BY avg(review.rating) DESC 
            LIMIT 10;';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        $result = [];
        foreach ($resultSet->fetchAllAssociative() as $row) {
            $roundRating = round($row["rating"] * 2) / 2;
            array_push(
                $result,
                array(
                    "id" => $row["restaurant_id"],
                    "name" => $row["name"],
                    "description" => $row["description"],
                    "rating" => $roundRating
                )
            );
        }

        return $result;
    }

    //    /**
    //     * @return Restaurant[] Returns an array of Restaurant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Restaurant
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
