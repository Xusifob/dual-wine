<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Api\Categorie;
use AppBundle\Entity\Api\Question;
use AppBundle\Entity\Api\Tour;
use AppBundle\Entity\Api\User;
use AppBundle\Entity\Api\UserRepository;
use AppBundle\Entity\Api\CategorieRepository;
use AppBundle\Entity\Api\QuestionRepository;
use AppBundle\Entity\Api\PartieRepository;
use AppBundle\Entity\Api\TourRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 * @package AppBundle\Controller
 *
 *
 *  @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/{id}", name="api_user", defaults={"id" = null}, requirements={"id" =  "\d+"})
     */
    public function userAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $user = $repo->SelectUsers($id);
        return new JsonResponse($user);
    }

    /**
     * @Route("/inscription", name="api_inscription")
     */
    public function userInscription()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (ici username password et e-mail)
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $inscription = $repo->RegisterUser($email,$username,$password);

        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($inscription);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connexion", name="api_connexion")
     */
    public function userConnexion()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (ici username password et e-mail)
        $username = $request->get('username');
        $password = $request->get('password');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $connexion = $repo->ConnectUser($username,$password);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($connexion);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/forgot-password", name="api_mdp_oublie")
     */
    public function userForgot()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (e-mail)
        $email = $request->get('email');
        /************** TRAITEMENT DES DONNEES ****************/
        // Je récupère l'entity manager
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $return = $repo->ForgetPassword($email);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($return);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/profil", name="api_profil")
     */
    public function userProfil()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (e-mail)
        $token = $request->get('token');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $profil = $repo->UserProfil($token);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($profil);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/profilupdate", name="api_profil_update")
     */
    public function userUpdate()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax
        $token = $request->get('token');
        $oldpassword = $request->get('oldpassword');
        $newpassword = $request->get('newpassword');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $return = $repo->UserUpdate($token,$oldpassword,$newpassword);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($return);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/classement", name="api_classement")
     */
    public function userClassement()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (e-mail)
        $token = $request->get('token');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $classement = $repo->UserClassement($token);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($classement);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/deletefriend", name="api_delete_friend")
     */
    public function userDeleteFriend()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (e-mail)
        $token = $request->get('token');
        $amis = $request->get('id');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $delete = $repo->DeleteFriend($token,$amis);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($delete);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/findfriend", name="api_find_friend")
     */
    public function userFindFriend()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (e-mail)
        $token = $request->get('token');
        $pseudo = $request->get('pseudo');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $find = $repo->FindFriend($token,$pseudo);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($find);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/friends", name="api_get_friends")
     */
    public function userGetFriends()
    {
        /************ RECUPERATION DES DONNEES ******************/
        // Je récupère la valeur de la requette
        $request = $this->get('request');
        // Je récupère les données envoyés en ajax (e-mail)
        $token = $request->get('token');
        $pseudo = $request->get('pseudo');
        /************** TRAITEMENT DES DONNEES ****************/
        // J
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repo */
        $repo = $em->getRepository('AppBundle:Api\User');
        $friends = $repo->GetFriends($token);
        /************ RETOUR DES DONNEES *********************/
        // Je crée ma valeur de retour
        $jsonResponse = new JsonResponse($friends);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/newpartie", name="api_new_partie")
     */
    public function userNewPartie()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');
        $id = $request->get('id');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->NewGame($token,$id);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;
    }

    /**
     * @Route("/connect/acceptpartie", name="api_accept_partie")
     */
    public function userAcceptPartie()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->AcceptGame($token,$id_partie=null);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;

    }

    /**
     * @Route("/connect/refusePartie", name="api_refuse_partie")
     */
    public function userRefusePartie()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');

        $id = $request->get('id');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->RefuseGame($token,$id);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;

    }

    /**
     * @Route("/connect/launchpartie", name="api_launch_partie")
     */
    public function userLaunchPartie()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');
        $id_partie = $request->get('id');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->LaunchGame($token,$id_partie);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;

    }

    /**
     * @Route("/connect/play", name="api_play")
     */
    public function Play()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');
        $id_partie = $request->get('id_part');
        $id_categorie = $request->get('id_cat');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->PlayGame($token,$id_partie,$id_categorie);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;

    }

    /**
     * @Route("/connect/finishplay", name="api_finish_play")
     */
    public function userFinishPlay()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');
        $id_partie = $request->get('id');
        $scoreJoueur = $request->get('scoreJoueur');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->FinishGame($token,$id_partie,$scoreJoueur);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;

    }

    /**
     * @Route("/connect/allpartie", name="api_all_partie")
     */
    public function userAllParties()
    {
        /************ RECUPERATION DES DONNEES ******************/
        //Je récupère la valeur de la requête
        $request = $this->get('request');
        //Je recupere les données envoyées en ajax
        $token = $request->get('token');
        /************** TRAITEMENT DES DONNEES ****************/
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Api\User');
        /** @var UserRepository $repo */
        $start = $repo->AllGame($token);
        /************ RETOUR DES DONNEES *********************/
        $jsonResponse = new JsonResponse($start);
        // Je set les headers pour pouvoir utiliser les données en ajax
        $jsonResponse->headers->set("Access-Control-Allow-Origin", "*");
        $jsonResponse->headers->set("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        $jsonResponse->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        // Je renvoie la réponse
        return $jsonResponse;

    }

    /**
     * @Route("/connect/cat", name="api_create_category")
     */
    public function createCat()
    {

        $cats = ['Culture générale','Histoire','Géographie','Vocabulaire','Oenologie'];
        foreach($cats as $ct) {
            $cat = new Categorie();
            $cat->setNom($ct);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($cat);
            $em->flush();
        }

        return new JsonResponse($cats);
    }

    /**
     * @Route("/connect/qst", name="api_create_qst")
     */
    public function createQst()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $catRepo = $em->getRepository('AppBundle:Api\Categorie');

        $cat = $catRepo->findOneBy(['nom' => 'Géographie']);

        $qsts = [
            [
                'nom' => 'Quel pays est le plus gros exportateur de vin aujourd\'hui ?',
                'Vrai' => 'France',
                'Faux1' => "Italie",
                'Faux2' => 'Espagne',
                'Faux3' => 'Chine',
            ],
            [
                'nom' => 'Quel pays est le plus gros consommateur de vin aujourd\'hui ?',
                'Vrai' => 'Vatican',
                'Faux1' => "France",
                'Faux2' => 'Etats Unis',
                'Faux3' => 'Chine',
            ],
            [
                'nom' => 'Quelle est la région qui produit le plus de vin en France?',
                'Vrai' => 'Aquitaine',
                'Faux1' => "Champagne",
                'Faux2' => 'Côte du Rhône',
                'Faux3' => 'Bourgogne',
            ],
            [
                'nom' => 'De quelle région vient le St Emilion ? ',
                'Vrai' => 'Bordeaux ',
                'Faux1' => "Champagne",
                'Faux2' => 'Côte du Rhône',
                'Faux3' => 'Bourgogne',
            ],
        ];

        foreach($qsts as $qst) {
            $question = new Question();
            $question->setNom($qst['nom'])
                ->setCategorie($cat)
                ->setFaux1($qst['Faux1'])
                ->setFaux2($qst['Faux2'])
                ->setFaux3($qst['Faux3'])
                ->setVrai($qst['Vrai'])
                ->setActive(true)
                ;
            $em->persist($question);
            $em->flush();
        }

        return new JsonResponse($qsts);
    }


}