<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RequestController extends BaseController
{
    /**
     * @Route("/requests", name= "requests_show")
     * @Template("AppBundle:Backend\Requests:index.html.twig")
     */
    public function indexAction(Request $request){
    	
    }

}
