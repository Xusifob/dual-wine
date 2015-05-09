<?php

namespace AppBundle\Entity\Api;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Api\PartieRepository")
 */
class Partie
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
     * @var integer
     *
     * @ORM\Column(name="score1", type="integer")
     */
    private $score1;

    /**
     * @var integer
     *
     * @ORM\Column(name="score2", type="integer")
     */
    private $score2;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $joueurs;

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
     * Set joueurs
     *
     * @param string $joueurs
     * @return Partie
     */
    public function setJoueurs($joueurs)
    {
        $this->joueurs = $joueurs;

        return $this;
    }

    /**
     * Get joueurs
     *
     * @return string
     */
    public function getJoueurs()
    {
        return $this->joueurs;
    }

    /**
     * Set score1
     *
     * @param integer $score1
     * @return Partie
     */
    public function setScore1($score1)
    {
        $this->score1 = $score1;

        return $this;
    }

    /**
     * Get score1
     *
     * @return integer 
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * Set score2
     *
     * @param integer $score2
     * @return Partie
     */
    public function setScore2($score2)
    {
        $this->score2 = $score2;

        return $this;
    }

    /**
     * Get score2
     *
     * @return integer 
     */
    public function getScore2()
    {
        return $this->score2;
    }
}
