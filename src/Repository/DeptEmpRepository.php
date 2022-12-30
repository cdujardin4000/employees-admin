<?php

namespace App\Repository;

use App\Entity\DeptEmp;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<DeptEmp>
 *
 * @method DeptEmp|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeptEmp|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeptEmp[]    findAll()
 * @method DeptEmp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeptEmpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeptEmp::class);



    }

    public function save(DeptEmp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DeptEmp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function insertInto(int $emp_no, string $dept_no, Datetime $from_date, DateTime $to_date): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "INSERT INTO `dept_emp` (`emp_no`, `dept_no`, `from_date`, `to_date`) VALUES (:emp_no, :dept_no, :from_date, :to_date)";

        $stmt = $conn->prepare($sql);

        $stmt->executeQuery([
            'emp_no' => $emp_no,
            'dept_no' => $dept_no,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);

    }

}