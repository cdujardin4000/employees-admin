<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
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
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Employee) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * @throws Exception
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
     * @throws Exception
     */
    public function findVeterans(): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT `emp_no`, `hire_date`, `first_name`, `last_name`, `ago` FROM `employees` ORDER BY `hire_date` ASC LIMIT 10";

        //dd($resultSet->fetchOne());
        return $conn->executeQuery($sql)->fetchAllAssociative();
        // returns an array of arrays (i.e. a raw data set)

    }

    /**
     * @throws Exception
     */
    public function getNbEmployeeByGender(): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "select gender
                , count( case when gender='M'
                then 1 end ) as Male
                , count( case when gender='F'
                then 1 end ) as Female
                , count( case when gender='X'
                then 1 end ) as Undefined
                FROM employees e GROUP BY gender
                INNER JOIN dept_emp de ON d.emp_no=de.emp_no
                    WHERE de.to_date='9999-01-01'";

        //dd($resultSet->fetchOne());
        return $conn->executeQuery($sql)->fetchAllAssociative();
        // returns an array of arrays (i.e. a raw data set)

    }

    /**
     * @throws Exception
     */
    public function findArrivals(): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT `emp_no`, `hire_date`, `first_name`, `last_name` FROM `employees` ORDER BY `hire_date` DESC LIMIT 10";

        //dd($resultSet->fetchOne());
        return $conn->executeQuery($sql)->fetchAllAssociative();
        // returns an array of arrays (i.e. a raw data set)

    }


    /**
     * @throws Exception
     */
    public function getCurrentDepartment(int $emp_no) : string
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT cde.dept_no  FROM current_dept_emp cde INNER JOIN departments d ON cde.dept_no = d.dept_no WHERE emp_no=:emp_no AND to_date='9999-01-01'";

        //dd($sql);
        $stmt = $conn->prepare($sql);

        $resultSet  = $stmt->executeQuery([
            'emp_no' => $emp_no,
        ]);


        //dd($resultSet);
        return  (string)$resultSet->fetchOne();
    }

    /**
     * @throws Exception
     */
    public function getCurrentTitle(int $emp_no) : string
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT et.title_no  FROM emp_title et INNER JOIN titles t ON t.title_no = t.title_no WHERE emp_no=:emp_no AND to_date='9999-01-01'";

        //dd($sql);
        $stmt = $conn->prepare($sql);

        $resultSet  = $stmt->executeQuery([
            'emp_no' => $emp_no,
        ]);


        //dd($resultSet);
        return  (string)$resultSet->fetchOne();
    }

}


