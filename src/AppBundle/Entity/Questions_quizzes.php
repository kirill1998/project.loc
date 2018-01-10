<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 04.01.2018
 * Time: 4:30
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Q_qRepository")
 * @ORM\Table(name="questions_quizzes")
 **/
class Questions_quizzes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;

    public function getId()
    {
        return $this->id;
    }
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
 private $quizzes;
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Questions")
     * @ORM\JoinColumn(nullable=false)
     */
 private $questions;

    /**
     * @return Quizzes
     */
    public function getQuizzes_id()
    {
        return $this->quizzes;
    }


    public function setQuizzes_id(Quizzes $quizzes)
    {
        $this->quizzes = $quizzes;
    }

    /**
     * @return Questions
     */
    public function getQuestions_id()
    {
        return $this->questions;
    }


    public function setQuestions_id(Questions $questions)
    {
        $this->questions = $questions;
    }

}