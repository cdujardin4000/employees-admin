<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 *
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function getLastDepartmentRemoveDAndAddOneBeforeAddingD(): string // :p
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT dept_no FROM departments ORDER BY dept_no DESC LIMIT 1";

        $resultSet = $conn->executeQuery($sql);
        $lastDept = (string)$resultSet->fetchOne();

        $nb = (int)substr($lastDept, -3) +1;
        return 'd0'.$nb;
    }

    /**
     * @throws Exception
     */
    public function getNbEmployees($dept_no): int
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT  COUNT(*)  FROM dept_emp WHERE dept_no LIKE :dept_no AND to_date LIKE '9999-01-01'";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery([
            'dept_no' => $dept_no,
        ]);
        //dd($resultSet->fetchOne());
        return  (int)$resultSet->fetchOne();
    }

    /**
     * @throws Exception
     */
    public function getManagerNo($dept_no)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT emp_no FROM dept_manager 
                WHERE dept_no='$dept_no'
                AND to_date='9999-01-01'";
        return $conn->executeQuery($sql)->fetchOne();
    }

    /**
     * @throws Exception
     */
    public function getManager($manager): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT first_name, last_name, email, photo FROM employees WHERE emp_no='$manager'";

        return $conn->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getAvailablePosts($dept_no): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM titles INNER JOIN dept_title ON dept_title.title_no = titles.title_no WHERE dept_no=:dept_no ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery([
            'dept_no' => $dept_no,
        ])->fetchAllAssociative();
    }
//
//     *@return Department[] Returns an array of Department objects
//
    /**  public function getManagerDemands($emp_no): int
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
    }**/
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Department
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
