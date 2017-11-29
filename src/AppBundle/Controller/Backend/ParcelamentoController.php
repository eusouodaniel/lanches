<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Parcelamento;
use AppBundle\Form\ParcelamentoType;

/**
 * Parcelamento controller.
 *
 * @Route("/backend/parcelamento")
 */
class ParcelamentoController extends BaseController {

    /**
     * @Route("/" , name="backend_parcelamento")
     * @Template("AppBundle:Backend\Parcelamento:index.html.twig")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository("AppBundle:Parcelamento")->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Parcelamento entity.
     *
     * @Route("/new/", name="backend_parcelamento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {

        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = new Parcelamento();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Creates a new Parcelamento entity.
     *
     * @Route("/create/", name="backend_parcelamento_create")
     * @Method("POST")
     * @Template("AppBundle:Backend\Parcelamento:new.html.twig")
     */
    public function createAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $entity = new Parcelamento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        try {
            if ($form->isValid()) {
                    $em->persist($entity);
                    $em->flush();

                    $request->getSession()
                            ->getFlashBag()
                            ->add('success', 'Registro criado com sucesso!');

                    return $this->redirect($this->generateUrl('backend_parcelamento'));
            }
        } catch (\Exception $e) {
            $this->log($e);
            $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Parcelamento entity.
     *
     * @param Parcelamento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Parcelamento $entity) {
        $form = $this->createForm(new ParcelamentoType(), $entity, array(
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Parcelamento entity.
     *
     * @Route("/{id}/edit", name="backend_parcelamento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Parcelamento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Parcelamento entity.');
            }

            $editForm = $this->createEditForm($entity);
            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'laboratory' => $entity
            );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Creates a form to edit a Parcelamento entity.
     *
     * @param Parcelamento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Parcelamento $entity) {
        $form = $this->createForm(new ParcelamentoType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Parcelamento entity.
     *
     * @Route("/{id}/update", name="backend_parcelamento_update")
     * @Template("AppBundle:Backend\Parcelamento:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Parcelamento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Parcelamento entity.');
            }

            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                    try {

                        

                        $em->flush();

                        $request->getSession()
                                ->getFlashBag()
                                ->add('success', 'Registro atualizado com sucesso!');

                        return $this->redirect($this->generateUrl('backend_parcelamento'));
                    } catch (\Exception $e) {
                        $this->log($e);
                        $request->getSession()
                                ->getFlashBag()
                                ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
                    }
            }

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Deletes a Parcelamento entity.
     *
     * @Route("/{id}/delete", name="backend_parcelamento_delete")
     *
     */
    public function deleteAction(Request $request, $id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Parcelamento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Parcelamento entity.');
            }

            $em->remove($entity);
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Registro excluído com sucesso!');

            return $this->redirect($this->generateUrl('backend_parcelamento'));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

}
