<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Api\UserRepository;
use AppBundle\Entity\Api\User;
class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function ajouterUser()
    {
        $User = new User;

        // J'ai raccourci cette partie, car c'est plus rapide à écrire !
        $form = $this->createFormBuilder($User)
            ->add('pseudo',       'text')
            ->add('email',       'text')
            ->add('password',       'password')
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

                $token = $User->createToken();
                $User->setToken($token)
                    ->cryptPassword();
                // On l'enregistre notre objet $User dans la base de données

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->persist($User);
                $em->flush();

                // On redirige vers la page de visualisation de l'User nouvellement créé
                return $this->redirect($this->generateUrl('homepage', array('id' => $User->getId())));
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
