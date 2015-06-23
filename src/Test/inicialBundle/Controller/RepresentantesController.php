<?php

namespace Test\inicialBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Test\inicialBundle\Entity\Representantes;
use Test\inicialBundle\Form\RepresentantesType;

/**
 * Representantes controller.
 *
 * @Route("/representantes")
 */
class RepresentantesController extends Controller
{

    /**
     * Lists all Representantes entities.
     *
     * @Route("/", name="representantes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('inicialBundle:Representantes')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Representantes entity.
     *
     * @Route("/", name="representantes_create")
     * @Method("POST")
     * @Template("inicialBundle:Representantes:nuevo.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Representantes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('representantes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Representantes entity.
     *
     * @param Representantes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Representantes $entity)
    {
        $form = $this->createForm(new RepresentantesType(), $entity, array(
            'action' => $this->generateUrl('representantes_nuevo'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Representantes entity.
     *
     * @Route("/new", name="representantes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Representantes();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Representantes entity.
     *
     * @Route("/{id}", name="representantes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('inicialBundle:Representantes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Representantes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Representantes entity.
     *
     * @Route("/{id}/edit", name="representantes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('inicialBundle:Representantes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Representantes entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Representantes entity.
    *
    * @param Representantes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Representantes $entity)
    {
        $form = $this->createForm(new RepresentantesType(), $entity, array(
            'action' => $this->generateUrl('representantes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Representantes entity.
     *
     * @Route("/{id}", name="representantes_update")
     * @Method("PUT")
     * @Template("inicialBundle:Representantes:editar.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('inicialBundle:Representantes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Representantes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('representantes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Representantes entity.
     *
     * @Route("/{id}", name="representantes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('inicialBundle:Representantes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Representantes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('representantes'));
    }

    /**
     * Creates a form to delete a Representantes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('representantes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
