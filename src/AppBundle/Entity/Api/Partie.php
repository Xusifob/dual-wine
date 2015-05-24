<?php

namespace AppBundle\Entity\Api;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var integer
     *
     * @ORM\Column(name="tourj1", type="integer")
     */
    private $tourj1;




    /**
     * @var integer
     *
     * @ORM\Column(name="tourj2", type="integer")
     */
    private $tourj2;




    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $joueur;




    /**
     * @var integer
     *
     * @ORM\Column(name="firstplayer", type="integer")
     */
    private $firstPlayer;




    /**
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="Tour")
     */
    private $tours;

    /**
     * @var integer
     *
     * @ORM\Column(name="etat", type="integer")
     */
    private  $etat;
    const ETAT_DEBUT = 0;
    const ETAT_ENCOURS = 1;
    const ETAT_FIN = 2;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joueur = new ArrayCollection();
        $this->tours = new ArrayCollection();
    }



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
     * Set firstPlayer
     *
     * @param string $firstPlayer
     * @return Partie
     */
    public function setFirstPlayer($fisrtPlayer)
    {
        $this->firstPlayer = $fisrtPlayer;

        return $this;
    }

    /**
     * Get joueurs
     *
     * @return string
     */
    public function getFirstPlayer()
    {
        return $this->firstPlayer;
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

    /**
     * Set tourj1
     *
     * @param integer $tourj1
     * @return Partie
     */
    public function setTourj1($tourj1)
    {
        $this->tourj1 = $tourj1;

        return $this;
    }

    /**
     * Get tourj1
     *
     * @return integer
     */
    public function getTourj1()
    {
        return $this->tourj1;
    }

    /**
     * Set tourj2
     *
     * @param integer $tourj2
     * @return Partie
     */
    public function setTourj2($tourj2)
    {
        $this->tourj2 = $tourj2;

        return $this;
    }

    /**
     * Get tourj2
     *
     * @return integer
     */
    public function getTourj2()
    {
        return $this->tourj2;
    }

    /**
     * @param $etat
     * @return string
     */
    public function setEtat($etat){
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }



    /**
     * @param User $joueur
     * @return $this
     */
    public function addJoueur(User $joueur)
    {
        $this->joueur[] = $joueur;

        return $this;
    }

    /**
     * Remove joueur
     *
     * @param User $joueur
     */
    public function removeJoueur(User $joueur)
    {
        $this->joueur->removeElement($joueur);
    }


    /**
     * @param Tour $tours
     * @return $this
     */
    public function addTour(Tour $tours)
    {
        $this->tours[] = $tours;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getJoueur()
    {
        return $this->joueur;
    }

    /**
     * @param ArrayCollection $joueur
     */
    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;
    }



    /**
     * Remove joueur
     *
     * @internal param User $joueur
     */
    public function removeTour(Tour $tour)
    {
        $this->tours->removeElement($tour);
    }

    /**
     * @return int
     */
    public function getTours()
    {
        return $this->tours;
    }

    /**
     * @param int $tours
     */
    public function setTours($tours)
    {
        $this->tours = $tours;
    }


}
