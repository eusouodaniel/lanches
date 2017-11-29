<?php

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;

/**
 * State controller.
 *
 * @Route("/state")
 */
class StateController extends BaseController {

    /**
     * Obtem as cidades de acordo com o estado informado.
     *
     * @Route("/list_ajax", name="state_item_ajax", options={"expose"=true})
     * @Method("post")
     * @Template()
     */
    public function itemAjaxAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = Request::createFromGlobals();
            $ufCode = $request->request->get('uf');

            $repository = $this->getStateRepository();
            $state = $repository->findOneByAcronym($ufCode);

            $return = array("responseCode" => 200, "results" => $state);
        } catch (\Exception $e) {
            $return = array("responseCode" => 400, 'errorMessage' => $e->getTraceAsString());
        }

        return $this->returnJson($return);
    }

    /**
     * Função que retorna o json para a requisição
     * @param array $return Array de retorno
     * @return \Rockbee\AppBundle\Controller\Response
     */
    public function returnJson($return) {
        $serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($return, 'json');
        return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

}
