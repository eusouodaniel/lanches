<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Affiliated;
use AppBundle\Entity\AffiliatedLaboratory;
use AppBundle\Form\AffiliatedType;

/**
 * Affiliated controller.
 *
 * @Route("/backend/affiliated")
 */
class AffiliatedController extends BaseController
{
    /**
     * @Route("/" , name="backend_affiliated")
     * @Template("AppBundle:Backend\Affiliated:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository("AppBundle:Affiliated")->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Affiliated entity.
     *
     * @Route("/new/", name="backend_affiliated_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

      $em = $this->getDoctrine()->getManager();


        $entity = new Affiliated();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Affiliated entity.
     *
     * @Route("/create/", name="backend_affiliated_create")
     * @Method("POST")
     * @Template("AppBundle:Backend\Affiliated:new.html.twig")
     */
    public function createAction(Request $request)
    {

      $em = $this->getDoctrine()->getManager();
      $affiliatedlaboratory = new AffiliatedLaboratory();

     
        $entity = new Affiliated();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
          if($this->validUsernameEmail($entity)){
            try{

              $email = $form->get('email')->getData();

              // Set entity fields custom
              $this->requestCustomForm($request, $entity);

              $entity->setUsername($email);
              $entity->setEnabled(true);
              $entity->setRoles(array("ROLE_USER"));

              $em->persist($entity);
              $em->flush();

              $laboratory = $em->getRepository("AppBundle:Laboratory")->findOneById(1);

              $affiliatedlaboratory->setAffiliated($entity);
              $affiliatedlaboratory->setLaboratory($laboratory);
              $em->persist($affiliatedlaboratory);
              $em->flush();

              $request->getSession()
                   ->getFlashBag()
                   ->add('success', 'Registro criado com sucesso!');
              if($this->getUser() == null){
                $this->sendEmail('Bem vindo ao sistema de lanches',$email,'
        Caro(a), '.$entity->getFirstName().'!<br>
        Aproveite os nossos lanches!<br><br>
        

');
                return $this->redirect($this->generateUrl('fos_user_security_login'));
              }else{
               return $this->redirect($this->generateUrl('backend_affiliated'));
              }
            }catch(\Exception $e)
            {
              $this->log($e);
                $request->getSession()
                ->getFlashBag()
                ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
            }
          }else{
            $request->getSession()
                ->getFlashBag()
                ->add('error', 'Email já cadastrado, tente novamente!');
          }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Affiliated entity.
     *
     * @param Affiliated $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Affiliated $entity)
    {
        $form = $this->createForm(new AffiliatedType(), $entity, array(
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Affiliated entity.
     *
     * @Route("/{id}/edit", name="backend_affiliated_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
      if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Affiliated')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Affiliated entity.');
        }

        $editForm = $this->createEditForm($entity);
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
      }else{
        throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
      }
    }

    /**
    * Creates a form to edit a Affiliated entity.
    *
    * @param Affiliated $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Affiliated $entity)
    {
        $form = $this->createForm(new AffiliatedType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Affiliated entity.
     *
     * @Route("/{id}/update", name="backend_affiliated_update")
     * @Template("AppBundle:Backend\Affiliated:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
      if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Affiliated')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Affiliated entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
          if($this->validUsernameEmail($entity)){
            try{
              // Set entity fields custom
              $this->requestCustomForm($request, $entity);

              $em->flush();

              $request->getSession()
                   ->getFlashBag()
                   ->add('success', 'Registro atualizado com sucesso!');

              return $this->redirect($this->generateUrl('backend_affiliated'));
            }catch(\Exception $e){
                $this->log($e);
                $request->getSession()
                ->getFlashBag()
                ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
            }
          }else{
            $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Email já cadastrado, tente novamente!');
          }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
      }else{
        throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
      }
    }
    /**
     * Deletes a Affiliated entity.
     *
     * @Route("/{id}/delete", name="backend_affiliated_delete")
     *
     */
    public function deleteAction(Request $request, $id)
    {
      if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Affiliated')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Affiliated entity.');
        }

        $em->remove($entity);
        $em->flush();

        $request->getSession()
             ->getFlashBag()
             ->add('success', 'Registro excluído com sucesso!');

        return $this->redirect($this->generateUrl('backend_affiliated'));
      }else{
        throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
      }
    }

     /**
     * Atualiza o Status do Usuários para ATIVO
     *
     * @Route("/?id={id}", name="backend_affiliated_status")
     */
    public function activateStatusAction($id)
    {
      if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Affiliated')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Affiliated entity.');
        }

        $condicao = $entity->getStatus();
        if($condicao == "Ativo")
           $entity->setEnabled(false);
        else
            $entity->setEnabled(true);

        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('backend_affiliated'));
      }else{
        throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
      }
    }

    /**
     * Set entity fields custom
     * @param Request $request
     * @param Entity $entity
     */
    public function requestCustomForm($request, $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $affiliatedCustomArray = $request->request->get('affiliated_custom');

        if (!empty($affiliatedCustomArray['plainPassword'])) {
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $new_pwd_encoded = $encoder->encodePassword($affiliatedCustomArray['plainPassword'], $entity->getSalt());
            $entity->setPassword($new_pwd_encoded);
        }
    }

private function sendEmail($titulo, $email, $message){
      $mailMessage = \Swift_Message::newInstance()
              ->setSubject($titulo)
              ->setFrom("contato@sistema.danielrodriguess.com")
              ->setTo($email)
              ->setContentType("text/html")
              ->setBody($message);

      $this->get('mailer')->send($mailMessage);
    }

}
