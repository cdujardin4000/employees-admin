<?php

namespace App\Repository;

use App\Entity\CarsEmp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarsEmp>
 *
 * @method CarsEmp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarsEmp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarsEmp[]    findAll()
 * @method CarsEmp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarsEmpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarsEmp::class);
    }

    public function save(CarsEmp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CarsEmp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findModel($car_id): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT 'model' FROM cars WHERE car_id=:car_id";
        //dd($id);
        $stmt = $conn->prepare($sql);


        return $stmt->executeQuery([
            'car_id' => $car_id,
        ])->fetchOne();




    }

//    /**
//     * @return CarsEmp[] Returns an array of CarsEmp objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CarsEmp
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
