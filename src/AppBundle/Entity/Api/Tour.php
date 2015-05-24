<?php

namespace AppBundle\Entity\Api;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tour
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Api\TourRepository")
 */
class Tour
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     */
    private $categorie;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     */
    private $question1;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     */
    private $question2;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     */
    private $question3;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Tour
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set question1
     *
     * @param string $question1
     * @return Tour
     */
    public function setQuestion1($question1)
    {
        $this->question1 = $question1;

        return $this;
    }

    /**
     * Get question1
     *
     * @return string 
     */
    public function getQuestion1()
    {
        return $this->question1;
    }

    /**
     * Set question2
     *
     * @param string $question2
     * @return Tour
     */
    public function setQuestion2($question2)
    {
        $this->question2 = $question2;

        return $this;
    }

    /**
     * Get question2
     *
     * @return string 
     */
    public function getQuestion2()
    {
        return $this->question2;
    }

    /**
     * Set question3
     *
     * @param string $question3
     * @return Tour
     */
    public function setQuestion3($question3)
    {
        $this->question3 = $question3;

        return $this;
    }

    /**
     * Get question3
     *
     * @return string 
     */
    public function getQuestion3()
    {
        return $this->question3;
    }
}
