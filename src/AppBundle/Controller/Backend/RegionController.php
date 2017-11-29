<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Region;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Region controller.
 *
 * @Route("backend/region")
 */
class RegionController extends BaseController
{
    /**
     * Lists all region entities.
     *
     * @Route("/", name="backend_region_index")
     * @Method("GET")
     * @Template("AppBundle:Backend\Region:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return array(
            'regions' => $regions,
        );
    }

    /**
     * Creates a new region entity.
     *
     * @Route("/new", name="backend_region_new")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Region:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $region = new Region();
        $form = $this->createForm('AppBundle\Form\RegionType', $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush($region);

            $request->getSession()
                 ->getFlashBag()
                 ->add('success', 'Registro criado com sucesso!');

            return $this->redirectToRoute('backend_region_index');
        }

        return array(
            'region' => $region,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing region entity.
     *
     * @Route("/{id}/edit", name="backend_region_edit")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Region:edit.html.twig")
     */
    public function editAction(Request $request, Region $region)
    {
        $editForm = $this->createForm('AppBundle\Form\RegionType', $region);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()
                 ->getFlashBag()
                 ->add('success', 'Registro atualizado com sucesso!');

            return $this->redirectToRoute('backend_region_edit', array('id' => $region->getId()));
        }

        return array(
            'region' => $region,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a region entity.
     *
     * @Route("/{id}/delete", name="backend_region_delete")
     */
    public function deleteAction(Request $request, Region $region)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($region);
        $em->flush($region);

        $request->getSession()
             ->getFlashBag()
             ->add('success', 'Registro excluído com sucesso!');

        return $this->redirectToRoute('backend_region_index');
    }
}
