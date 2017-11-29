<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Laboratory;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Laboratory controller.
 *
 * @Route("backend/laboratory")
 */
class LaboratoryController extends BaseController {

    /**
     * Lists all laboratory entities.
     *
     * @Route("/", name="backend_laboratory_index")
     * @Method("GET")
     * @Template("AppBundle:Backend\Laboratory:index.html.twig")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $laboratories = $em->getRepository('AppBundle:Laboratory')->findAll();
        $distributors = $em->getRepository('AppBundle:Distributors')->findAll();

        return array(
            'laboratories' => $laboratories,
            'distributors' => $distributors
        );
    }

    /**
     * Creates a new laboratory entity.
     *
     * @Route("/new", name="backend_laboratory_new")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Laboratory:new.html.twig")
     */
    public function newAction(Request $request) {
        $laboratory = new Laboratory();
        $form = $this->createForm('AppBundle\Form\LaboratoryType', $laboratory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Formatação de valor
            $priceValue = (str_replace(".", "", $laboratory->getMinimunValue()));
            $priceValue = (str_replace(",", ".", $priceValue));
            if ($laboratory->getMinimunValue() != null) {
                $laboratory->setMinimunValue($priceValue);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($laboratory);
            $em->flush($laboratory);

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Registro criado com sucesso!');

            return $this->redirectToRoute('backend_laboratory_index');
        }

        return array(
            'laboratory' => $laboratory,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing laboratory entity.
     *
     * @Route("/{id}/edit", name="backend_laboratory_edit")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Laboratory:edit.html.twig")
     */
    public function editAction(Request $request, Laboratory $laboratory) {

        $em = $this->getDoctrine()->getManager();

        $originalLaboratoryAgentsAction = array();

        // Cria um array com agentRegions já existentes
        foreach ($laboratory->getLaboratoryagents() as $laboratoryAgents) {
            $originalLaboratoryAgentsAction[] = $laboratoryAgents;
        }

        $editForm = $this->createForm('AppBundle\Form\LaboratoryType', $laboratory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            //Formatação do preço
            //$priceValue = (str_replace(".", "", $laboratory->getMinimunValue()));
            $priceValue = (str_replace(",", ".", $laboratory->getMinimunValue()));
            if ($laboratory->getMinimunValue() != null) {
                $laboratory->setMinimunValue($priceValue);
            }

            // Filtra o array $originalLaboratoryAgentsAction para conter apenas itens que não existam mais
            foreach ($laboratory->getLaboratoryagents() as $laboratoryAgents) {
                foreach ($originalLaboratoryAgentsAction as $key => $toDel) {
                    if ($toDel->getId() === $laboratoryAgents->getId()) {
                        unset($originalLaboratoryAgentsAction[$key]);
                    }
                }
            }

            // Remove os dias que foram apagadas
            foreach ($originalLaboratoryAgentsAction as $laboratoryAgents) {
                $em->remove($laboratoryAgents);
            }

            $this->getDoctrine()->getManager()->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Registro atualizado com sucesso!');

            return $this->redirectToRoute('backend_laboratory_edit', array('id' => $laboratory->getId()));
        }

        $priceValue = number_format($laboratory->getMinimunValue(), 2, ',', '.');
        $laboratory->setMinimunValue($priceValue);

        return array(
            'laboratory' => $laboratory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a laboratory entity.
     *
     * @Route("/{id}/delete", name="backend_laboratory_delete")
     */
    public function deleteAction(Request $request, Laboratory $laboratory) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($laboratory);
        $laboratory->unlinkImages();
        $em->flush($laboratory);
        $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro excluído com sucesso!');

        return $this->redirectToRoute('backend_laboratory_index');
    }

}
