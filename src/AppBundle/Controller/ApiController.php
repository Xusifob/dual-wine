<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Api\UserRepository;
use AppBundle\Entity\Api\CategorieRepository;
use AppBundle\Entity\Api\QuestionRepository;
use AppBundle\Entity\Api\PartieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class APIController
 * @package AppBundle\Controller
 *
 *
 *  @Route("/api")
 */
class APIController extends Controller
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
        $return = ['inscription' => $inscription];
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



}