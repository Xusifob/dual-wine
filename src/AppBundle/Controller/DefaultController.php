<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Api\User;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/", name="homepage")
     */
    public function registerAction()
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
     * @Route("/", name="homepage")
     */
    public function ajouterUser()
    {
        $User = new User;

        // J'ai raccourci cette partie, car c'est plus rapide à écrire !
        $form = $this->createFormBuilder($User)
            ->add('username',       'text')
            ->add('mail',       'text')
            ->add('password',       'text')
            ->getForm();

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $User contient les valeurs entrées dans le formulaire par le visiteur
            $form->bind($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                // On l'enregistre notre objet $User dans la base de données
                $em = $this->getDoctrine()->getManager();
                $em->persist($User);
                $em->flush();

                // On redirige vers la page de visualisation de l'User nouvellement créé
                return $this->redirect($this->generateUrl('user_voir', array('id' => $User->getId())));
            }
        }

        // À ce stade :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
