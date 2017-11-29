<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Distributors;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Distributors controller.
 *
 * @Route("backend/distributors")
 */
class DistributorsController extends BaseController
{

    /**
     * Lists all distributors entities.
     *
     * @Route("/", name="backend_distributors_index")
     * @Method("GET")
     * @Template("AppBundle:Backend\Distributors:index.html.twig")
     */
    public function indexAction()
    {
        $em           = $this->getDoctrine()->getManager();
        $distributors = $em->getRepository('AppBundle:Distributors')->findAll();
        return array(
            'distributors' => $distributors,
        );
    }

    /**
     * Creates a new distributors entity.
     *
     * @Route("/new", name="backend_distributors_new")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Distributors:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $distributors = new Distributors();
        $form         = $this->createForm('AppBundle\Form\DistributorsType', $distributors);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($distributors);
            $em->flush($distributors);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro criado com sucesso!');

            return $this->redirectToRoute('backend_distributors_index');
        }

        return array(
            'distributors' => $distributors,
            'form'         => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing distributors entity.
     *
     * @Route("/{id}/edit", name="backend_distributors_edit")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Distributors:edit.html.twig")
     */
    public function editAction(Request $request, Distributors $distributors)
    {
        $editForm = $this->createForm('AppBundle\Form\DistributorsType', $distributors);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro atualizado com sucesso!');

            return $this->redirectToRoute('backend_distributors_edit', array('id' => $distributors->getId()));
        }

        return array(
            'distributors' => $distributors,
            'edit_form'    => $editForm->createView(),
        );
    }

    /**
     * Deletes a distributors entity.
     *
     * @Route("/{id}/delete", name="backend_distributors_delete")
     */
    public function deleteAction(Request $request, Distributors $distributors)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($distributors);
        $em->flush($distributors);

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Registro excluído com sucesso!');

        return $this->redirectToRoute('backend_distributors_index');
    }

}
