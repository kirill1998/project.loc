<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 04.01.2018
 * Time: 7:34
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Questions_quizzes;
use Doctrine\ORM\EntityRepository;

class Q_qRepository  extends EntityRepository
{
    /**
     * @return Questions_quizzes[]
     */
    public function findFunFactOrderedBySize()
    {

    }
}