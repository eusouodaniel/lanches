<?php

namespace AppBundle\Service;

use AppBundle\Entity\Payment;
use Doctrine\ORM\EntityManager;
use Tear\MoipBundle\Services\Moip;

class MoipManager {

    protected $container;
    protected $em;
    protected $logger;
    protected $moip_token;
    protected $moip_key;
    protected $moip_environment;
    private $translator;

    public function __construct($container, EntityManager $em, $logger, $translator, $moip_token, $moip_key, $moip_environment) {
        $this->container = $container;
        $this->em = $em;
        $this->logger = $logger;
        $this->moip_token = $moip_token;
        $this->moip_key = $moip_key;
        $this->moip_environment = $moip_environment;
        $this->translator = $translator;
    }

    /**
     * Através do pedido gerado pelo site é gerado um novo pedido para a compra com o moip
     *
     * @param GatewayReturn $gatewayReturn Variável de retorno
     * @param Payment $payment Registro de pagamento
     * @param Agenda $agenda Registro de agenda
     * @param User $user Usuário logado
     * @return array $resultArray Resultado do processamento do cartão de crédito, informando se o mesmo foi processado ou não.
     */
    public function generateOrder($gatewayReturn, $payment, $agenda, $user) {

        $moip = new Moip();

        //Seta os dados de acesso ao moip
        $moip->setCredential(array(
          'key' => $this->moip_key,
          'token' => $this->moip_token
        ));

        //Seta o ambiente
        $moip->setEnvironment($this->moip_environment);

        //Moip - dados de pagamento
        $moip->setUniqueID("".$payment->getId().""); //Id único
        $moip->setValue($payment->getValue()); //Valor
        $moip->setReason("Teste"); //Descrição do serviço

        $moip->validate('Basic');

        //Moip - Comissionamento
        /*$moip->addComission(
        'Pagamento do serviço '.$agenda->getService()->getCategory()->getName() . " - " . $agenda->getService()->getName(), // Descrição do split
        $agenda->getProfessional()->getEmail(), //Login moip do commisionado
        '65.00', //Porcentagem de repasse
        true); //Informa que valor da comissão é repasse*/

        //Moip - Cliente
        //$moip->setPayer(array('name' => $user()->getFullName(),
        //'email' => $user()->getEmail()));

        //print_r($moip->send());

        $moip->send();
        $moipResponse = $moip->getAnswer();

        //print_r($moipResponse);

        if($moipResponse->getResponse()==1){
          $gatewayReturn->setSuccess(true);
          $gatewayReturn->setUrl($moipResponse->getPayment_url());
        }else{
          $gatewayReturn->setSuccess(false);
          $gatewayReturn->setOrderStatus($moipResponse->getError());
        }

        return $gatewayReturn;
    }

    /**
     * Através do pedido gerado pelo site é gerado um novo pedido para a compra com o moip
     *
     * @param GatewayReturn $gatewayReturn Variável de retorno
     * @param Payment $payment Registro de pagamento
     * @param Lista de agendas $agendaList Registro de agenda
     * @param User $user Usuário logado
     * @return array $resultArray Resultado do processamento do cartão de crédito, informando se o mesmo foi processado ou não.
     */
    public function generateIndentification($gatewayReturn, $payment, $agendaList, $user) {

        $moip = new Moip();

        //Seta os dados de acesso ao moip
        $moip->setCredential(array(
          'key' => $this->moip_key,
          'token' => $this->moip_token
        ));

        //Seta o ambiente
        $moip->setEnvironment($this->moip_environment);

        //Moip - dados de pagamento
        $moip->setUniqueID("".$payment->getId().""); //Id único
        $moip->setValue($payment->getValue()); //Valor
        $moip->setReason("Pagamento de Agendamento - Bella Moça"); //Descrição do serviço

        $moip->setPayer(array('name' => $user->getFullName(),
        'email' => $user->getEmail(),
        'payerId' => $user->getId(),
        'billingAddress' => array(
          'address' => $user->getAddress(),
          'number' => "",
          'complement' => "",
          'city' => $user->getDistrict()->getCity(),
          'neighborhood' => $user->getDistrict(),
          'state' => $user->getDistrict()->getCity()->getState()->getAcronym(),
          'country' => 'BRA',
          'zipCode' => $user->getZipcode(),
          'phone' => $user->getPhone()
        )));
        $moip->validate('Identification');

        //Moip - Comissionamento
        /*foreach ($agendaList as $agenda) {
          $moip->addComission(
          'Pagamento do serviço '.$agenda->getService()->getCategory()->getName() . " - " . $agenda->getService()->getName(), // Descrição do split
          $agenda->getProfessional()->getGatewayAccount(), //Login moip do commisionado
          $agenda->getProfessionalValue(), //Porcentagem de repasse
          false);
        }*/

        $moip->send();
        $moipResponse = $moip->getAnswer();

        if($moipResponse->getResponse()==1){
          $gatewayReturn->setSuccess(true);
          $gatewayReturn->setOrderStatus($moip->getAnswer()->token);
        }else{
          $gatewayReturn->setSuccess(false);
          $gatewayReturn->setOrderStatus($moipResponse->getError());
        }

        return $gatewayReturn;
    }

}
