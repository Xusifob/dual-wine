<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Api\Question;
use AppBundle\Form\Api\QuestionType;

/**
 * Api\Question controller.
 *
 * @Route("/admin")
 */
class QuestionController extends Controller
{

    /**
     * Lists all Api\Question entities.
     *
     * @Route("/dashboard", name="admin_question")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Api\Question')->findAll();

        return $this->render('AppBundle:Api:Question\index.html.twig',[
            'entities' => $entities]
        );
    }

    /**
     * Lists all Api\Question entities.
     *
     * @Route("/", name="admin_home")
     * @Method("GET")
     */
    public function connexionAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Api\Question')->findAll();

        return $this->render('AppBundle:Api:Question\connexion.html.twig',[
            'entities' => $entities]
        );
    }
    /**
     * Creates a new Api\Question entity.
     *
     * @Route("/dashboard", name="admin_question_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new Question();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:Api:Question\new.html.twig',[
            'entity' => $entity,
            'form'   => $form->createView()]
        );
    }

    /**
     * Creates a form to create a Api\Question entity.
     *
     * @param Question $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Question $entity)
    {
        $form = $this->createForm(new QuestionType(), $entity, array(
            'action' => $this->generateUrl('admin_question_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Api\Question entity.
     *
     * @Route("/new", name="admin_question_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $entity = new Question();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:Api:Question\new.html.twig',[
            'entity' => $entity,
            'form'   => $form->createView()]
        );
    }

    /**
     * Finds and displays a Api\Question entity.
     *
     * @Route("/{id}", name="admin_question_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Api\Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Api\Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Api:Question\show.html.twig',[
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()]
        );
    }

    /**
     * Displays a form to edit an existing Api\Question entity.
     *
     * @Route("/{id}/edit", name="admin_question_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Api\Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Api\Question entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Api:Question\edit.html.twig',[
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()]
        );
    }

    /**
    * Creates a form to edit a Api\Question entity.
    *
    * @param Question $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Question $entity)
    {
        $form = $this->createForm(new QuestionType(), $entity, array(
            'action' => $this->generateUrl('admin_question_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Api\Question entity.
     *
     * @Route("/{id}", name="admin_question_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Api\Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Api\Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_question_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:Api:Question\edit.html.twig',[
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()]
        );
    }
    /**
     * Deletes a Api\Question entity.
     *
     * @Route("/{id}", name="admin_question_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Api\Question')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Api\Question entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_question'));
    }

    /**
     * Creates a form to delete a Api\Question entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_question_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
