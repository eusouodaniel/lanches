<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Cotacoes;
use AppBundle\Form\CotacoesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cotacoes controller.
 *
 * @Route("backend/cotacoes")
 */
class CotacoesController extends BaseController
{

    /**
     * Lists all distributors entities.
     *
     * @Route("/", name="backend_cotacoes_index")
     * @Method("GET")
     * @Template("AppBundle:Backend\Cotacoes:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cotacoes = $em->getRepository('AppBundle:Cotacoes')->findByUser($this->getUser());
        return array(
            'cotacoes' => $cotacoes,
        );
    }

    /**
     * Lists all distributors entities.
     *
     * @Route("/representante", name="backend_cotacoes_representante_index")
     * @Method("GET")
     * @Template("AppBundle:Backend\Cotacoes:index.html.twig")
     */
    public function indexRepresentanteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $verify = '';
        $agent = $em->getRepository('AppBundle:Agent')->find($this->getUser());
        if($agent != null){
            if($agent->getPerfumery() == 1){
                $verify = 'perfumery';
            }
        }
        if($verify != null){
            $cotacoes = $em->getRepository('AppBundle:Cotacoes')->findAll();
        }else{
            $cotacoes = $em->getRepository('AppBundle:Cotacoes')->findByUser($this->getUser());
        }
        return array(
            'cotacoes' => $cotacoes,
        );
    }

    /**
     * Creates a new cotacoes entity.
     *
     * @Route("/new", name="backend_cotacoes_new")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Cotacoes:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $cotacoes = new Cotacoes();
        $form         = $this->createForm('AppBundle\Form\CotacoesType', $cotacoes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $cotacoes->setUser($this->getUser());
            $em->persist($cotacoes);
            $em->flush($cotacoes);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro criado com sucesso!');

            return $this->redirectToRoute('backend_cotacoes_index');
        }

        return array(
            'cotacoes' => $cotacoes,
            'form'         => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing cotacoes entity.
     *
     * @Route("/{id}/edit", name="backend_cotacoes_edit")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Backend\Cotacoes:edit.html.twig")
     */
    public function editAction(Request $request, Cotacoes $cotacoes)
    {
        $editForm = $this->createForm('AppBundle\Form\CotacoesType', $cotacoes);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro atualizado com sucesso!');

            return $this->redirectToRoute('backend_cotacoes_edit', array('id' => $cotacoes->getId()));
        }

        return array(
            'cotacoes' => $cotacoes,
            'edit_form'    => $editForm->createView(),
        );
    }

    /**
     * Deletes a cotacoes entity.
     *
     * @Route("/{id}/delete", name="backend_cotacoes_delete")
     */
    public function deleteAction(Request $request, Cotacoes $cotacoes)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cotacoes);
        $em->flush($cotacoes);

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Registro excluído com sucesso!');

        return $this->redirectToRoute('backend_cotacoes_index');
    }

}
