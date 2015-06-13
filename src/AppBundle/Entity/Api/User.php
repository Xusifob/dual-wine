<?php

namespace AppBundle\Entity\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Api\UserRepository")
 */
class User
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=30)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=255, nullable=true)
     */
    private $score;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $amis;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->amis = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     * @return User
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }



    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param User $friend
     * @return $this
     */
    public function addFriend(User $friend)
    {
        $this->amis[] = $friend;

        return $this;
    }

    /**
     * Remove friend
     *
     * @param User $friend
     */
    public function removeFriend(User $friend)
    {
        $this->amis->removeElement($friend);
    }

    /**
     * Get amis
     *
     * @return ArrayCollection
     */
    public function getAmis()
    {
        return $this->amis;
    }

    /**
     * Create token
     *
     * @param int $size
     * @return int
     */
    public function createToken($size = 20)
    {
        $string = "";
        $chaine = "012345azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN6789";	// J'associe tous les caractères que je veux mettre dans mon id
        srand((double)microtime()*time());
        for($i=0; $i<$size; $i++) {		// J'affiche le bon nombre de caractères.
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }

    public function createPassword($size = 8)
    {
        $string = "";
        $mdp = "012345azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN6789";	// J'associe tous les caractères que je veux mettre dans mon id
        srand((double)microtime()*time());
        for($i=0; $i<$size; $i++) {		// J'affiche le bon nombre de caractères.
            $string .= $mdp[rand()%strlen($mdp)];
        }
        return $string;
    }

    /**
     * Retourne si le mot de passe est le bon
     *
     * @param $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password,$this->password);
    }

    public function cryptPassword()
    {
        $this->password = crypt($this->password);
        return $this;
    }

    /**
     * @param $newPassword
     * @return bool
     */
    public function sendNewPassword($newPassword){

        //TODO Envoyer un mail un peu plus beau cf :http://openclassrooms.com/courses/e-mail-envoyer-un-e-mail-en-php
        return mail($this->email,"mot de passe oublié",$newPassword);
    }

    public function UpdateScore()
    {

    }


    public function AlgorithmeElo($ScoreJ1,$ScoreJ2,$nbpartieJ1,$nbPartieJ2,$W1,$W2){

        // Coefficient K
        $K1 = $this->CalculK($nbpartieJ1);
        $K2 = $this->CalculK($nbPartieJ2);

        $D1 = $ScoreJ1 - $ScoreJ2;

        // Calcul des probabilités
        $pD1 = 1/(1+10^(-$D1/400));
        $pD2 = 1- $pD1;

        // Calcul des scores
        $ScoreJ1 = $ScoreJ1 + $K1*($W1 - $pD1 );
        $ScoreJ2 = $ScoreJ2 + $K2*($W2 - $pD2 );

        return [
            'ScoreJ1' => $ScoreJ1,
            'ScoreJ2' => $ScoreJ2
        ];
    }


    function CalculK($nbPartie){
        $K = 0;
        if($nbPartie<15) {
            $K = 40;
        }else if($nbPartie<30){
            $K = 30;
        }else if($nbPartie<40){
            $K = 20;
        }else{
            $K = 10;
        }
        return $K;
    }
}
