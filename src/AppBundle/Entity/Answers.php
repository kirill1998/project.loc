<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 04.01.2018
 * Time: 5:28
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnwersRepository")
 * @ORM\Table(name="answers")
 **/
class Answers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;
    /**
     * @Assert\NotBlank()
     *@ORM\Column(type="integer")
     * @ORM\JoinColumn(nullable=false)
     */
      private $questions_id;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */private $text;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     */private $type;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    public function getQuestions_id()
    {
        return $this->questions_id;
    }


    public function setQuestions_id(  $questions)
    {
        $this->questions_id = $questions;
    }


    public function getText()
    {
        return $this->text;
    }


    public function setText(string $text)
    {
        $this->text = $text;
    }


    public function getType()
    {
        return $this->type;
    }


    public function setType(bool $type)
    {
        $this->type = $type;
    }

}