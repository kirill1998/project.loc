<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 22.12.2017
 * Time: 5:26
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class GenusRepository extends EntityRepository
{
    /**
     * @return Genus[]
     */
    public function findFunFactOrderedBySize()
    {
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.funFact = :oct')
            ->setParameter('oct', 'qwert')
            ->orderBy('genus.speciesCount', 'DESC')
            ->getQuery()
            ->execute();
    }
}