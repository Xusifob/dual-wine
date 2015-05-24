<?php

namespace AppBundle\Entity\Api;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Api\QuestionRepository")
 */
class Question
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="vrai", type="string", length=100)
     */
    private $vrai;

    /**
     * @var string
     *
     * @ORM\Column(name="faux1", type="string", length=100)
     */
    private $faux1;

    /**
     * @var string
     *
     * @ORM\Column(name="faux2", type="string", length=100)
     */
    private $faux2;

    /**
     * @var string
     *
     * @ORM\Column(name="faux3", type="string", length=100)
     */
    private $faux3;

    /**
     * @var Categorie
     *
     * @ORM\OneToMany(targetEntity="Categorie",mappedBy="id")
     */
    private $categorie;


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
     * Set nom
     *
     * @param string $nom
     * @return Question
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set vrai
     *
     * @param string $vrai
     * @return Question
     */
    public function setVrai($vrai)
    {
        $this->vrai = $vrai;

        return $this;
    }

    /**
     * Get vrai
     *
     * @return string 
     */
    public function getVrai()
    {
        return $this->vrai;
    }

    /**
     * Set faux1
     *
     * @param string $faux1
     * @return Question
     */
    public function setFaux1($faux1)
    {
        $this->faux1 = $faux1;

        return $this;
    }

    /**
     * Get faux1
     *
     * @return string 
     */
    public function getFaux1()
    {
        return $this->faux1;
    }

    /**
     * Set faux2
     *
     * @param string $faux2
     * @return Question
     */
    public function setFaux2($faux2)
    {
        $this->faux2 = $faux2;

        return $this;
    }

    /**
     * Get faux2
     *
     * @return string 
     */
    public function getFaux2()
    {
        return $this->faux2;
    }

    /**
     * Set faux3
     *
     * @param string $faux3
     * @return Question
     */
    public function setFaux3($faux3)
    {
        $this->faux3 = $faux3;

        return $this;
    }

    /**
     * Get faux3
     *
     * @return string 
     */
    public function getFaux3()
    {
        return $this->faux3;
    }

    /**
     * Set categorie
     *
     * @param Categorie $categorie
     * @return Question
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}
