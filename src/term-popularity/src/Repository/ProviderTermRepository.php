<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProviderTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProviderTerm>
 *
 * @method ProviderTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProviderTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProviderTerm[]    findAll()
 * @method ProviderTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProviderTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProviderTerm::class);
    }

    public function add(ProviderTerm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProviderTerm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTermScoreByProvider(array $parameters): array
    {
        $q = $this->createQueryBuilder('pt')
            ->select('pt.score as score,  p.name as provider, t.text as term')
            ->where('t.text = :term')
            ->setParameter('term', $parameters['term'])
            ->andWhere('p.name = :provider')
            ->setParameter('provider', $parameters['provider'])
            ->join('pt.provider', 'p', 'WITH', 'p.id = pt.provider')
            ->join('pt.term', 't', 'WITH', 't.id = pt.term')
            ->getQuery();

        return $q->getResult();
    }
}
