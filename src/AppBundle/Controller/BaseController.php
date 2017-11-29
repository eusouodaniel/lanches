<?php

namespace AppBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller {

    /**
     * Adds support for magic finders for repositories.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return object The found repository.
     * @throws \BadMethodCallException If the method called is an invalid find* method
     *                                 or no find* method at all and therefore an invalid
     *                                 method call.
     */
    public function __call($method, $arguments) {
      if (preg_match('/^get(\w+)Repository$/', $method, $matches)) {
        return $this->getDoctrine()->getRepository('AppBundle:' . $matches[1]);
      } else {
        throw new \BadMethodCallException(
            "Undefined method '$method'. Provide a valid repository name!");
      }
    }
    
    /**
     * Função que retorna o json para a requisição
     * @param array $return Array de retorno
     * @return Response
     */
    public function returnJson($return) {
        /*$serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($return, 'json');*/
        $serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($return, 'json');
        return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

    /**
     * Retorna o service "logger".
     * @return \Monolog\Logger
     */
    protected function getLogger() {
        return $this->get("logger");
    }

    /**
     * Método para retornar o objeto User.
     * @return CoreBundle\Entity\User
     */
    public function getUser() {
        return parent::getUser();
    }

    /**
     * Retorna o repositório de user.
     * @return AppBundle\Repository\UserRepository
     */
    protected function getUserRepository(){
        return $this->getDoctrine()->getRepository('UserBundle:User');
    }

    /**
     * Método para verificar acesso.
     * @return createNotFoundException
     */
    public function verifyAdmin() {
      if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
      }
    }

    /**
     * Valida se o username e email são válidos
     * @param User $entity
     * @return boolean $flag
     */
    function validUsernameEmail($entity) {
        $repository = $this->getUserRepository();
        $flag = $repository->getUserByNameAndUsername($entity);

        return $flag;
    }
    
    /**
     * Atalho para geração de logs no sistema.
     * @param string $message Mensagem a ser incluida no log.
     * @param string $level Level do log. Default: error.
     */
    protected function log($message, $level = "error") {
        $this->getLogger()->log($level, $message);
    }

    public function getDataPorExtenso()
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return strftime('%d de %B de %Y', strtotime('today'));

    }
}
