<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Agent;
use AppBundle\Form\AgentType;

/**
 * Agent controller.
 *
 * @Route("/backend/agent")
 */
class AgentController extends BaseController {

    /**
     * @Route("/" , name="backend_agent")
     * @Template("AppBundle:Backend\Agent:index.html.twig")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository("AppBundle:Agent")->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Agent entity.
     *
     * @Route("/new/", name="backend_agent_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {

        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = new Agent();
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
     * Creates a new Agent entity.
     *
     * @Route("/create/", name="backend_agent_create")
     * @Method("POST")
     * @Template("AppBundle:Backend\Agent:new.html.twig")
     */
    public function createAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $entity = new Agent();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        try {
            if ($form->isValid()) {
                if ($this->validUsernameEmail($entity)) {

                    $email = $form->get('email')->getData();

                    // Set entity fields custom
                    $this->requestCustomForm($request, $entity);

                    $entity->setUsername($email);
                    $entity->setEnabled(true);
                    $entity->setRoles(array("ROLE_USER"));

                    $em->persist($entity);
                    $em->flush();

                    $request->getSession()
                            ->getFlashBag()
                            ->add('success', 'Registro criado com sucesso!');

                    return $this->redirect($this->generateUrl('backend_agent'));
                } else {
                    $request->getSession()
                            ->getFlashBag()
                            ->add('error', 'Email já cadastrado, tente novamente!');
                }
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
     * Creates a form to create a Agent entity.
     *
     * @param Agent $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Agent $entity) {
        $form = $this->createForm(new AgentType(), $entity, array(
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Agent entity.
     *
     * @Route("/{id}/edit", name="backend_agent_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Agent')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agent entity.');
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
     * Creates a form to edit a Agent entity.
     *
     * @param Agent $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Agent $entity) {
        $form = $this->createForm(new AgentType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Agent entity.
     *
     * @Route("/{id}/update", name="backend_agent_update")
     * @Template("AppBundle:Backend\Agent:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Agent')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agent entity.');
            }

            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            $originalAgentRegionsAction = array();

            // Cria um array com agentRegions já existentes
            foreach ($entity->getAgentregions() as $agentRegions) {
                $originalAgentRegionsAction[] = $agentRegions;
            }

            if ($editForm->isValid()) {
                if ($this->validUsernameEmail($entity)) {
                    try {

                        // Filtra o array $originalAgentRegionsAction para conter apenas itens que não existam mais
                        foreach ($entity->getAgentregions() as $agentRegions) {
                            foreach ($originalAgentRegionsAction as $key => $toDel) {
                                if ($toDel->getId() === $agentRegions->getId()) {
                                    unset($originalAgentRegionsAction[$key]);
                                }
                            }
                        }

                        // Remove os dias que foram apagadas
                        foreach ($originalAgentRegionsAction as $agentRegions) {
                            $em->remove($agentRegions);
                        }

                        // Set entity fields custom
                        $this->requestCustomForm($request, $entity);

                        $em->flush();

                        $request->getSession()
                                ->getFlashBag()
                                ->add('success', 'Registro atualizado com sucesso!');

                        return $this->redirect($this->generateUrl('backend_agent'));
                    } catch (\Exception $e) {
                        $this->log($e);
                        $request->getSession()
                                ->getFlashBag()
                                ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
                    }
                } else {
                    $request->getSession()
                            ->getFlashBag()
                            ->add('error', 'Email já cadastrado, tente novamente!');
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
     * Deletes a Agent entity.
     *
     * @Route("/{id}/delete", name="backend_agent_delete")
     *
     */
    public function deleteAction(Request $request, $id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Agent')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agent entity.');
            }

            $em->remove($entity);
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Registro excluído com sucesso!');

            return $this->redirect($this->generateUrl('backend_agent'));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Atualiza o Status do Usuários para ATIVO
     *
     * @Route("/?id={id}", name="backend_agent_status")
     */
    public function activateStatusAction($id) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Agent')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agent entity.');
            }

            $condicao = $entity->getStatus();
            if ($condicao == "Habilitado")
                $entity->setEnabled(false);
            else
                $entity->setEnabled(true);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_agent'));
        }else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Set entity fields custom
     * @param Request $request
     * @param Entity $entity
     */
    public function requestCustomForm($request, $entity) {
        $em = $this->getDoctrine()->getManager();
        $agentCustomArray = $request->request->get('agent_custom');

        if (!empty($agentCustomArray['plainPassword'])) {
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $new_pwd_encoded = $encoder->encodePassword($agentCustomArray['plainPassword'], $entity->getSalt());
            $entity->setPassword($new_pwd_encoded);
        }
    }

}
