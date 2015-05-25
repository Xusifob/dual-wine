<?php

namespace AppBundle\Entity\Api;

use AppBundle\Controller\ApiController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr as Expr;
use Doctrine\ORM\AbstractQuery;
use AppBundle\Entity\Api\Tour;
use AppBundle\Entity\Api\TourRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function SelectUsers($id = null)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u,a')
            ->leftJoin('u.amis','a', Expr\Join::WITH)
            ->orderBy('u.id', 'DESC')
        ;
        if($id != null){
            $qb->where('u.id = :id')
                ->setParameters([
                    ':id' => $id,
                ])
            ;
        }
        return null === $id
            ? $qb->getQuery()->getArrayResult()
            : $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function RegisterUser($email,$pseudo,$password)
    {
        if(!empty($email) && !empty($pseudo) && !empty($password)) {
            $em = $this->getEntityManager();

            // Je vérifie qu'un utilisateur n'a pas déja le même pseudo
            $user = $this->findOneBy(['email' => $email]);

            // S'il vaut null, l'utilisateur n'existe pas
            if ($user == null) {
                $user = $this->findOneBy(['pseudo' => $pseudo]);

                // S'il vaut null, l'utilisateur n'existe pas
                if ($user == null) {


                    // Je crée un nouvel utilisateur
                    $user = new User();
                    $user->setEmail($email);
                    $user->setPassword(crypt($password));
                    $user->setPseudo($pseudo);
                    $user->setScore(1000);
                    $user->setToken($user->createToken());

                    // Je l'ajoute dans la base de données
                    $em->persist($user);

                    $em->flush();

                    return ['inscription' => true];

                } else {
                    return ['inscription' => false];
                }
            } else {
                return ['inscription' => false];
            }
        }else{
            return ['inscription' => false];
        }
    }

    public function ConnectUser($pseudo, $password)
    {
        if( !empty($pseudo) && !empty($password)) {
            $em = $this->getEntityManager();

            // Je vérifie qu'un utilisateur n'a pas déja le même pseudo
            /**@var $user User **/
            $user = $this->findOneBy(['pseudo' => $pseudo]);


            // Gestion du token
            $token = $user->createToken();
            // Je modifie le token
            $user->setToken($token);

            // Je l'ajoute dans la base de données
            $em->persist($user);

            $em->flush();

            // Si le mot de passe est le bon
            if($user->verifyPassword($password)){
                return [
                    'token' => $token,
                    'pseudo' => $user->getPseudo(),
                    'connect' => true,
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                ];
            }else{
                return ['connect' => false];
            }
        }else{
            return ['connect' => false];
        }
    }

    public function ForgetPassword($email){
        if( !empty($email)) {
            $em =$this->getEntityManager();
            /**
             * @var $user User
             */
            $user = $this->findOneBy(['email'=>$email]);

            //envoyer un mail à l'adresse entré avec le nouveau mdp
            if ($user != null){
                $newpassword = $user->createPassword();
                $user->setPassword(crypt($newpassword));
                $em->persist($user);
                $em->flush();
                $ok = $user->sendNewPassword($newpassword);
                if($ok) {
                    return ['email' => true];
                }
                else{
                    return ['email' => false];
                }
            } else {
                return ['email' => false];
            }
        } else {
            return ['email' => false];
        }
    }

    public function UserProfil($token){
        if(!empty($token)){

            /**
             * @var $user User
             */
            $user = $this->findOneBy(['token'=>$token]);

            $classement = $this->getClassement($user);

            if($user != null){
                return [
                    'score'=>$user->getScore(),
                    'classement' => $classement +1,
                ];
            } else{
                return ['token'=>false];
            }
        }else{
            return ['token'=>false];
        }
    }

    public function UserUpdate($token,$oldpasword,$newpassword)
    {
        if (!empty($token) && !empty($oldpasword) && !empty($newpassword)) {
            $em = $this->getEntityManager();

            /**
             * @var $profil User
             */
            $profil = $this->findOneBy(['token' => $token]);
            if ($profil != null) {
                if ($profil->verifyPassword($oldpasword)) {
                    $profil->setPassword(crypt($newpassword));
                    $em->persist($profil);
                    $em->flush();

                    return [
                        'update' => true
                    ];
                } else {
                    return [
                        'password' => $profil->getPassword(),
                        'update' => false
                    ];
                }
            } else {
                return [
                    'update' => false
                ];
            }
        } else {
            return ['update' => false];
        }
    }

    public function UserClassement($token){
        if(!empty($token)){
            $em=$this->getEntityManager();

            /**
             * @var $user User
             */
            $user = $this->findOneBy(['token'=>$token]);
            if($user) {
                $qb = $this->createQueryBuilder('u');
                $qb->select('u.score', 'u.pseudo')
                    ->orderBy('u.score', 'DESC')
                    ->setMaxResults(10);
                $classements = $qb->getQuery()->getArrayResult();

                $classement = $this->getClassement($user);

                return  ['firsts' => $classements, 'me' => $classement +1];

            }else{
                return ['token' => false];
            }
        }else{
            return ['token'=>false];
        }

    }

    /**
     * @param User $user
     * @return int
     */
    public function getClassement($user)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.id','u.score')
            ->orderBy('u.score', 'DESC')
            ->where('u.score < :score')
            ->setParameters([
                ':score' => $user->getScore(),
            ]);
        return count($qb->getQuery()->getArrayResult());
    }

    public function DeleteFriend($token,$amis){
        if(!empty($token)&& !empty($amis)){
            $em = $this->getEntityManager();
            /**
             * @var $user User
             */
            $user = $this->findOneBy(['token' => $token]);

            $amis = $this->find($amis);

            if($amis != null && $user != null){
                $user->removeFriend($amis);
                $em->persist($user);
                $em->flush();

                return ['delete' => true];

            }else{
                return ['delete' => false];
            }
        }else{
            return ['delete' => false];
        }
    }

    public function GetFriends($token)
    {
        if(!empty($token)){
            $em = $this->getEntityManager();

            /**
             * @var $user User
             */
            $qb = $this->createQueryBuilder('u');
            $qb->select('u,a')
                ->leftJoin('u.amis','a', Expr\Join::WITH)
                ->orderBy('u.id', 'DESC')
                ->where('u.token = :token')
                ->setParameters([
                    ':token' => $token,
                ])
            ;
            $user = $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_ARRAY);

            if($user != null) {

                return [
                    'friend' => $user['amis'],
                ];
            }else{
                return ['friend' => false];
            }
        }
        else{
            return ['friend' => false];
        }
    }

    public function FindFriend($token,$username){
        if(!empty($token)&& !empty($username)){
            $em=$this->getEntityManager();

            /**
             * @var $user User
             */
            $user = $this->findOneBy(['token'=>$token]);



            $friend = $this->findOneBy(['pseudo' => $username]);


            if($user != null && $friend != null) {
                $user->addFriend($friend);
                $em->persist($user);
                $em->flush();
                return [
                    'friend' => true,
                ];
            }else{
                return ['friend' => false];
            }
        }
        /*
        Données récupérés :
        token
        id
        pseudo

        Traitement :
        Récupère le pseudo envoyé par l’utilisateur et cherche qqn avec un pseudo semblable dans la base
        S’il y a qqn avec le même pseudo, l’ajouter en ami

        Données renvoyés :
            Si l’ami est trouvé:
        friend(true)
            Si l’ami n’est pas trouvé:
        friend(false)*/
    }

    public function NewGame($token,$id_amis){
        if(!empty($token)) {
            /** @var EntityManager $em */
            $em = $this->getEntityManager();

            // Je récupère l'utilisateur
            /**
             * @var $user User
             */
            $user = $this->findOneBy(['token' => $token]);
            if($user != null) {
                $id_amis = $id_amis == 'random' ? null : $id_amis;

                if (null != ($id_amis)) {
                    $qb = $this->createQueryBuilder('u');
                    $qb->select('u,a')
                        ->leftJoin('u.amis', 'a', Expr\Join::WITH)
                        ->where('u.id = :id_amis')
                        ->setParameters([
                            ':id_amis' => $id_amis,
                        ]);
                    $joueur = $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_OBJECT);


                } else {
                    // Je récupère le nombre d'utilisateurs
                    $qb = $this->createQueryBuilder('u');
                    $qb->select('COUNT(u)');

                    // J'en récupère un seul
                    $result = $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_ARRAY);
                    $nbmax = (int)$result[1] - 1;
                    $alea = rand(0, $nbmax);
                    $qb->select('u')
                        ->where('u.id = :id')
                        ->setParameters([
                            ':id' => $alea,
                        ]);

                    // Je récupère cet utilisateur
                    $joueur = $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
                }
                /** @var Partie $partie */
                $partie = new Partie();
                $partie
                    ->setFirstPlayer($user->getId())
                    ->setScore1(0)
                    ->setScore2(0)
                    ->setTourj1(0)
                    ->setTourj2(0)
                    ->addJoueur($user)
                    ->addJoueur($joueur)
                    ->setEtat(Partie::ETAT_DEBUT);
                $em->persist($partie);
                $em->flush();
                return ['partie' => true,
                    'idPartie' => $partie->getId()];

            }else{
                return ['partie' => false];
            }
        }else{
            return ['partie' => false];
        }
    }
    /*
       Données récupérés :
        •	token
        •	id // id de l’utilisateur contre lequel la partie sera jouée
        Traitement :
        Récupère l’id et le token.

        Si l’id est null, récupère un joueur aléatoirement dans la liste des joueurs, sinon, récupère le joueur de l’id correspondant.

        Crée une nouvelle partie ayant :

        Joueurs : les 2 joueurs
        Score J1 = 0 ;
        Score J2 = 0 ;
        firstPlayer -> one to one (user) le joueur lié à l’id
        TourJ1 = 0 ;
        TourJ2 = 0 ;
        Etat = 0 ;

        Données renvoyés :
            Si les identifiants sont les bons :
        •	partie = true ;

        Si les identifiants ne sont pas les bons :

        •	partie = false ;

     * */

    public function AcceptGame($token,$id_partie){
        if(!empty($token)){
            $qb = $this->createQueryBuilder('p');
            $qb->select('p.id')
                ->where('p.id = :id_partie')
                ->setParameters([
                    ':id_partie' => $id_partie
                ])
            ;
            $id=  $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_ARRAY);
            $partie = new Partie();
            $partie->setEtat(1)
            ;
        }else{
            return[
                'partie'=>false
            ];
        }
        return[
            'partie'=>true,
            'id_partie'=>$id
        ];

    }
    /*
             Données récupérés :
        •	idPartie
        •	token
        Traitement :
        Récupère l’id de la partie,
        Modifie l’etat de la partie en 1

        Données renvoyés :
            Si les identifiants sont les bons :
        •	partie = true ;
        •	idPartie ;

        Si les identifiants ne sont pas les bons :

        •	partie = false ;
*/

    public function RefuseGame($token,$id_partie){
        if(!empty($token)){

            $user = $this->findBy(['token' => $token]);

            if($user !== null) {

                /** @var EntityManager $em */
                $em = $this->getEntityManager();


                // Je récupère la partie
                /** @var PartieRepository $repo */
                $repoPartie = $em->getRepository('AppBundle:Api\Partie');

                // Je récupère la partie
                $partie = $repoPartie->find($id_partie);

                // Je la supprime
                $em->remove($partie);
                $em->flush();

                return ['partie' => true];

            }
        }else{
            return[
                'partie'=>false
            ];
        }
    }
    /*
             Données récupérés :
        •	idPartie
        •	token
        Traitement :
        Récupère l’id de la partie,
        Supprime la partie

        Données renvoyés :
            Si les identifiants sont les bons :
        •	partie = true ;

        Si les identifiants ne sont pas les bons :

        •	partie = false ;

     */

    public function LaunchGame($token,$id_partie){
        if(!empty($token)) {

            /** @var User $user */
            $user = $this->findOneBy(['token' => $token]);
            if ($user != null) {

                /** @var EntityManager $em */
                $em = $this->getEntityManager();


                // Je récupère la partie
                /** @var PartieRepository $repo */
                $repoPartie = $em->getRepository('AppBundle:Api\Partie');

                /** @var Partie $partie */
                $partie = $repoPartie->find($id_partie);


                // Je regarde si l'utilisateur est le premier ou pas
                if($partie->getFirstPlayer() == $user->getId()) {
                    $userTour = $partie->getTourj1();
                    if ($userTour % 2 == 1) {

                        // Je récupère 4 catégories
                        // Je récupère la partie
                        /** @var CategorieRepository $repo */
                        $repoCategorie = $em->getRepository('AppBundle:Api\Categorie');
                        $cat = $repoCategorie->findAll();

                        // Je récupère 4 catégories aléatoires
                        shuffle($cat);
                        $cat = array_chunk($cat, 4, true)[0];

                        return [
                            'partie' => true,
                            'newtour' => true,
                            'categories' => $cat,
                        ];

                    } else {

                        // Je récupère tous les tours
                        $tours = $partie->getTours();

                        // Je récupère le dernier tour
                        /** @var Tour $tour */
                        $tour = $tours[count($tours) - 1];
                        return [
                            'partie' => true,
                            'newtour' => false,
                            'tour' => $tour->getId()
                        ];
                    }
                }else{
                    $userTour = $partie->getTourj1();
                    if ($userTour % 2 == 0) {

                        // Je récupère 4 catégories
                        // Je récupère la partie
                        /** @var CategorieRepository $repo */
                        $repo = $em->getRepository('AppBundle:Api\Categorie');
                        $cat = $repo->QueryCategories();

                        // Je récupère 4 catégories aléatoires
                        shuffle($cat);
                        $cat = array_chunk($cat, 4, true)[0];

                        return [
                            'partie' => true,
                            'newtour' => true,
                            'categories' => $cat,
                        ];

                    } else {

                        // Je récupère tous les tours
                        $tours = $partie->getTours();

                        // Je récupère le dernier tour
                        $tour = $tours[count($tours) - 1];
                        return [
                            'partie' => true,
                            'newtour' => false,
                            'tour' => $tour
                        ];
                    }
                }
            } else {
                return [
                    'partie' => false
                ];

            }
        }
        else{
            return[
                'partie'=>false
            ];
        }
    }
    /*
            Données récupérés :
        •	idPartie
        •	token
        Traitement :
        Récupère l’id de la partie,
        Regarde si l’utilisateur (via son token) est le premier joueur (firstPlayer)
        Si c’est le cas :
            Récupère son tour (TourJ1)
                Si son tour est paire, choisi 4 catégorie
                Si son tour est impaire, retourne le dernier tour
        Si c’est pas le cas :
            Récupère son tour (TourJ2)
                Si son tour est impaire, choisi 4 catégorie
                Si son tour est paire, retourne le dernier tour


        Données renvoyés :
            Si les identifiants sont les bons :
        •	partie = true ;
        •	newtour = true/false (en fonction de ce qui est retourné)
        •	tour (si le tour est retourné tour créé à l’occasion avec les questions et les réponses)
        •	categories (S’il y a un choix des catégories les 4 catégories choisis)

        Si les identifiants ne sont pas les bons :

        •	partie = false ;

     * */

    public function PlayGame($token,$id_partie,$id_categorie){
        if(!empty($token)) {

            $user = $this->findOneBy(['token' => $token]);
            if ($user != null) {

                /** @var EntityManager $em */
                $em = $this->getEntityManager();

                /** @var PartieRepository $partieRepo */
                $partieRepo = $em->getRepository('AppBundle:Api\Partie');

                /** @var Partie $partie */
                $partie = $partieRepo->find($id_partie);

                // Création du tour
                if ($id_categorie != null) {

                    /*--------------------------------*/
                    // Questions
                    /*--------------------------------*/
                    /** @var QuestionRepository $qstRepo */
                    $qstRepo = $em->getRepository('AppBundle:Api\Question');
                    $qst = $qstRepo->findBy(['active' => true]);

                    // Je mélange les qst
                    shuffle($qst);
                    // J'en resort 3
                    $qst = array_chunk($qst, 3, true)[0];

                    /*--------------------------------*/
                    // Catégories
                    /*--------------------------------*/

                    /** @var CategorieRepository $catRepo */
                    $catRepo = $em->getRepository('AppBundle:Api\Categorie');

                    $cat = $catRepo->find($id_categorie);



                    // Création du nouveau tour
                    $tour = new Tour();
                    $tour
                        ->setCategorie($cat)
                        ->setQuestion1($qst[0])
                        ->setQuestion2($qst[1])
                        ->setQuestion3($qst[2]);

                    $em->persist($tour);
                    $em->flush();

                    // J'ajoute le tour à la partie

                    if ($partie->getEtat() == Partie::ETAT_DEBUT) {
                        $partie->setEtat(Partie::ETAT_ENCOURS);
                    }
                    $partie->addTour($tour);

                    $em->persist($partie);
                    $em->flush();


                    /** @var TourRepository $tourRepo */
                    $tourRepo = $em->getRepository('AppBundle:Api\Tour');
                    $tour = $tourRepo->queryTour($tour->getId());

                    return [
                        'play' => true,
                        'tour' => $tour,
                    ];

                } else {

                    /** @var Tour $tour */
                    $tour = $partie->getTours()[count($partie->getTours())-1];
                    /** @var TourRepository $tourRepo */
                    $tourRepo = $em->getRepository('AppBundle:Api\Tour');
                    $tour = $tourRepo->queryTour($tour->getId());

                    return
                        [
                            'play' => true,
                            'tour' => $tour,
                        ];
                }
            }else{
                return ['play' => false];
            }
        }else{
            return ['play' => false];
        }
    }
    /*
             Données récupérés :
        •	token
        •	idPartie
        •	idCategorie
        Traitement :
        Crée un nouveau tour avec 3 questions récupérées au hasard dans la catégorie
        Ajoute ce tour dans les tours de la partie

        Données renvoyés :
            Si les identifiants sont les bons :
        •	play = true ;
        •	tour (tour créé à l’occasion avec les questions et les réponses)

        Si les identifiants ne sont pas les bons :

        •	play = false ;

     */

    public function FinishGame($token,$id_partie,$scoreJoueur){
        if(!empty($token)){

            /** @var User $user */
            $user = $this->findOneBy(['token' => $token]);

            if($user != null) {

                /** @var EntityManager $em */
                $em = $this->getEntityManager();

                /** @var PartieRepository $partieRepo */
                $partieRepo = $em->getRepository('AppBundle:Api\Partie');

                /** @var Partie $partie */
                $partie = $partieRepo->find($id_partie);
                if($user->getId() == $partie->getFirstPlayer()){
                    $partie->setTourj1($partie->getTourj1()+1);
                    $partie->setScore1($partie->getScore1()+$scoreJoueur);
                }else{
                    $partie->setTourj2($partie->getTourj2()+1);
                    $partie->setScore2($partie->getScore2()+$scoreJoueur);
                }

                if($partie->getTourj1() == 4 || $partie->getTourj2() == 4){

                    $partie->setEtat(Partie::ETAT_FIN);

                    // J'upload le score
                    /** @var User $firstPlayer */
                    $firstPlayer = $partie->getJoueur()[0];
                    /** @var User $secondPlayer */
                    $secondPlayer = $partie->getJoueur()[1];

                    $nbPartiesJ1 = $partieRepo->AllPartiesUser($firstPlayer->getToken());
                    $nbPartiesJ2 = $partieRepo->AllPartiesUser($secondPlayer->getToken());

                    if($partie->getScore1() > $partie->getScore2()){
                        $W1 = 1;
                        $W2 = 0;
                    }elseif($partie->getScore1() < $partie->getScore2()){
                        $W1 = 0;
                        $W2 = 1;
                    }else{
                        $W1 = 0.5;
                        $W2 = 0.5;
                    }


                    $score = $user->AlgorithmeElo(
                        $firstPlayer->getScore(),
                        $secondPlayer->getScore(),
                        $nbPartiesJ1,
                        $nbPartiesJ2,
                        $W1,
                        $W2
                    );

                    $firstPlayer->setScore($score['ScoreJ1']);
                    $secondPlayer->setScore($score['ScoreJ2']);
                    $em->persist($firstPlayer);
                    $em->persist($secondPlayer);
                }

                $em->persist($partie);
                $em->flush();

                return ['partie' => true];

            }else{
                return ['partie' => false];
            }
        }else{
            return ['partie' => false];
        }
    }
    /*
             Données récupérés :
        •	token
        •	idPartie
        •	scoreJoueur
        Traitement :
        Update la partie. Regarde si le joueur est le joueur qui commence (firstPlayer), si c’est le cas, ajoute scoreJoueur au scoreJ1, sinon ajoute scoreJoueur au scoreJ2
        Augmente son tour (TourJ1 si c’est le firstplayer et TourJ2 si c’est pas)
        Si Tour1 et Tour2 valent 4, fini la partie (Etat = 2)


        Données renvoyés :
            Si les identifiants sont les bons :
        •	play = true ;
        •	tour (tour créé à l’occasion avec les questions et les réponses)

        Si les identifiants ne sont pas les bons :

        •	play = false ;

     */

    public function AllGame($token){
        if (!empty($token)) {

            $user = $this->findOneBy(['token' => $token]);

            if ($user !== null) {
                /** @var EntityManager $em */
                $em = $this->getEntityManager();

                /** @var PartieRepository $repo */
                $repo = $em->getRepository('AppBundle:Api\Partie');
                return $repo->AllPartiesUser($token,true);

            } else {
                return ['play' => false];
            }
        }else {
            return ['play' => false];
        }
    }
    /*
             Données récupérés :
        •	token
        Traitement :
        Récupère toutes les parties de l’utilisateur

        Données renvoyés :
            Si les identifiants sont les bons :
        •	parties (tableau comportant toutes les parties)
        play = true ;
        Si les identifiants ne sont pas les bons :

        •	play = false ;
     */

}