<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);



    }

    public function save(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findLatest(): int
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT `emp_no` FROM `employees` ORDER BY `created_at` DESC LIMIT 1";

        $resultSet = $conn->executeQuery($sql);
        //dd($resultSet->fetchOne());
        return  (int)$resultSet->fetchOne();
        // returns an array of arrays (i.e. a raw data set)

    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getActualDepartment() : string
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT `dept_no` FROM `dept_emp` WHERE `to_date` LIKE '9999%'";
        $resultSet = $conn->executeQuery($sql);
        return  (string)$resultSet->fetchOne();
    }

}