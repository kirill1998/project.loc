<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 03.01.2018
 * Time: 3:47
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Questions;
use Doctrine\ORM\EntityRepository;

class QuestionsRepository extends EntityRepository
{
    /**
     * @return Questions[]
     */

    public function findFunFactOrderedBySize($name)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.name = :name')
            ->setParameter('name', $name)
            ->orderBy('q.id', 'DESC')
            ->getQuery()
            ->execute();
    }
}