<?php

namespace App\Repository;

use App\Entity\Intern;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Intern>
 *
 * @method Intern|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intern|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intern[]    findAll()
 * @method Intern[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intern::class);
    }

    public function save(Intern $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Intern $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function findActualInterns($id) : array
    {

        $today = new DateTime;
        // automatically knows to select Products
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('i')
            ->where('i.end_date >= :today')
            ->andWhere('i.start_date < :today')
            ->andWhere('i.emp = :id')
            ->orWhere('i.emp is NULL')
            ->setParameters([
                'today' => $today->format('Y-m-d'),
                'id' => $id
            ]);


        return $qb->getQuery()->execute();


/*        $today = new DateTime('now');
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM interns WHERE `end_date` >= :today  AND `start_date`<:today  AND `emp`=:id  OR`emp` IS NULL";
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery([
            'id' => $id,
            'today' => $today->format('Y-m-d')

        ])->fetchAllAssociative();*/
    }



//public function findOneBySomeField($value): ?Intern
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
