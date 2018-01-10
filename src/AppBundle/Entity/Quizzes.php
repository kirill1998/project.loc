<?php


namespace AppBundle\Entity;



use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizzesRepository")
 * @ORM\Table(name="quizzes")
 * @UniqueEntity(fields={"name"}, message="not unique name of quiz")
 */
class Quizzes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getnumber_of_players()
    {
        return $this->number_of_players;
    }

    /**
     * @param mixed $number_of_players
     */
    public function setnumber_of_players($number_of_players)
    {
        $this->number_of_players = $number_of_players;
    }
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */

    private $date;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $status;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     *
     */
    private $number_of_players;

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}