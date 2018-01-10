<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\U_qRepository")
 * @ORM\Table(name="users_quizzes")
 **/
class Users_quizzes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;


    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizzes;
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;
    /**
     *
     * @ORM\Column(type="integer")
     */


    private $done_answers;

    /**
     * @return mixed
     */
    public function getDoneAnswers()
    {
        return $this->done_answers;
    }

    /**
     * @param mixed $done_answers
     */
    public function setDoneAnswers($done_answers)
    {
        $this->done_answers = $done_answers;
    }

    /**
     * @return mixed
     */
    public function getRightAnswers()
    {
        return $this->right_answers;
    }

    /**
     * @param mixed $right_answers
     */
    public function setRightAnswers($right_answers)
    {
        $this->right_answers = $right_answers;
    }
    /**
     * @ORM\Column(type="integer")
     */
    private $right_answers;


    public function getisComplete()
    {
        return $this->is_complete;
    }

    public function setIsComplete($is_complete)
    {
        $this->is_complete = $is_complete;
    }
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     */
    private $is_complete;

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
     * @return User
     */
    public function getUser_id()
    {
        return $this->users;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function setUser_id(User $users)
    {
        $this->users = $users;
    }

}