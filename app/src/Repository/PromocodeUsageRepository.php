<?php

namespace App\Repository;

use App\Entity\PromocodeUsage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PromocodeUsage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromocodeUsage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromocodeUsage[]    findAll()
 * @method PromocodeUsage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromocodeUsageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromocodeUsage::class);
    }
}
