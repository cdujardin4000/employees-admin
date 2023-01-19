<?php

namespace App\Repository;

use App\Entity\Demand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demand>
 *
 * @method Demand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demand[]    findAll()
 * @method Demand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demand::class);
    }

    public function save(Demand $entity, bool $flush = false): void
    {
        //dd($entity);
        $this->getEntityManager()->persist($entity);
        //dd($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getManagerDemands($emp_no): int
    {

        $conn = $this->getEntityManager()->getConnection();
        $dep = $emp_no->getUser()->supervisions;
        dd($dep);
        $sql = "SELECT  *  FROM demands WHERE dept_no LIKE :emp_no AND to_date LIKE '9999-01-01'";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery([
            'dept_no' => $emp_no,
        ]);
        //dd($resultSet->fetchOne());
        return  (int)$resultSet->fetchOne();
    }

}
