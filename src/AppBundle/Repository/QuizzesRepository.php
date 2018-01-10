<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 22.12.2017
 * Time: 5:26
 */

namespace AppBundle\Repository;
use AppBundle\Entity\Quizzes;
use Doctrine\ORM\EntityRepository;

class QuizzesRepository extends EntityRepository
{
    /**
     * @return Quizzes[]
     */
    public function findFunFactOrderedBySize()
    {

    }
}