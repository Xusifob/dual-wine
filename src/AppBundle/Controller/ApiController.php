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
     * @Route("/", name="api_user", defaults={"id" = null}, requirements={"id" =  "\d+"})
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

}