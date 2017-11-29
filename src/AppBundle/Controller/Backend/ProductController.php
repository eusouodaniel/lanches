<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Product;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Pedido;
use AppBundle\Entity\LaboratoryShop;
use AppBundle\Entity\ShopUser;
use AppBundle\Entity\ConteudoPedido;
use AppBundle\Entity\ProductImport;
use AppBundle\Entity\ProductLaboratory;
use AppBundle\Entity\ShopLaboratory;
use AppBundle\Form\PedidoType;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
* Product controller.
*
* @Route("backend/product")
*/
class ProductController extends BaseController {

/**
 * Lists all product entities.
 *
 * @Route("/", name="backend_product_index")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:index.html.twig")
 */
public function indexAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $products = $em->getRepository('AppBundle:Product')->findAllOrderProduct();

        /** @var  $paginator */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($products, $request->query->get('page', 1), 20);

        return [
            'products' => $pagination,
            'usuario' => $this->getUser()
        ];
    }else{
        return $this->redirect($this->generateUrl('backend_product_shop'));
    }
}

/**
 * Lists all product.
 *
 * @Route("/laboratory/{id}/products", name="backend_product_laboratory_admin")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listlaboratoryproductadmin.html.twig")
 */
public function laboratoryProductAdminAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
    $laboratory = $em->getRepository('AppBundle:Laboratory')->find($id);
    $laboracys = $em->getRepository('AppBundle:ProductLaboratory')->findAllByLaboratory($id);
    
    /** @var  $paginator */
    $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate($laboracys, $request->query->get('page', 1), 40);

    return [
        'id' => $id,
        'laboratory' => $laboratory,
        'pagination' => $pagination,
    ];
}

/**
 * Creates a new product entity.
 *
 * @Route("/{laboratory}/deleteAll", name="backend_product_delete_all_post_admin")
 * @Method({"GET", "POST"})
 * @Template()
 */
public function deleteAllActionAdminPost(Request $request, $laboratory) {
      if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:ProductLaboratory')->findByLaboratory($laboratory);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Affiliated entity.');
        }

        foreach ($entity as $e) {
            $em->remove($e);
        }

        $em->flush();

        $request->getSession()
             ->getFlashBag()
             ->add('success', 'Registro excluído com sucesso!');

        return $this->redirect($this->generateUrl('backend_product_laboratory_admin', array('id'=> $laboratory)));
      }else{
        throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
      }
}

/**
 * Creates a new product entity.
 *
 * @Route("/delete_admin", name="backend_product_delete_post_admin")
 * @Method({"GET", "POST"})
 * @Template()
 */
public function deleteActionPostAdmin(Request $request) {
    $ids = $request->get("id");
    $ids = split(",", $ids);
    $count = 0;
    $laboratory = $request->get("laboratoryId");

    foreach ($ids as $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository("AppBundle:Product")->find($id);
            //$product->unlinkImages();
            $em->remove($product);
            $count++;
        } catch (\Exception $ex) {
            $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Ocorreu um erro ao excluir os registros.');
            $this->log($ex->getMessage());
            return $this->redirectToRoute('backend_product_laboratory_admin', array('id' => $laboratory));
        }
    }

    $em->flush();

    $request->getSession()
            ->getFlashBag()
            ->add('success', 'Registro excluído com sucesso!');
    return $this->redirectToRoute('backend_product_laboratory_admin', array('id'=> $laboratory));
}

/**
 * Lists all product entities.
 *
 * @Route("/search", name="search_product")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product\Search:show.html.twig")
 */
public function searchAction(Request $request, $parametro = null, $laboratory = null) {
    $em = $this->getDoctrine()->getManager();
    $parametro = $this->getRequest()->get("query");
    $laboratory = $this->getRequest()->get("laboratory");

    //Se a pesquisa é por produtos dentro de um determinado laboratório
    if($laboratory != null){
        $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->findByLaboratoryAndProductName($laboratory, $parametro);
        $affiliated = $em->getRepository('AppBundle:AffiliatedLaboratory')->findByAffiliated($this->getUser());

        /** @var  $paginator */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($productlaboratory, $request->query->get('page', 1), 40);

    }
    else{
        $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->findByProductName($parametro);
        $affiliated = $em->getRepository('AppBundle:AffiliatedLaboratory')->findByAffiliated($this->getUser());

        /** @var  $paginator */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($productlaboratory, $request->query->get('page', 1), 40);
        
    }
    $cart = '';
    foreach ($productlaboratory as $value) {
        $cart = $em->getRepository('AppBundle:Cart')->findBy(array('product' => $value, 'user' => $this->getUser()));
    }

    return [
        'pagination' => $pagination,
        'usuario' => $this->getUser(),
        'affiliated' => $affiliated,
        'carts' => $cart,
        'id' => $laboratory
    ];

}

/**
 * Lists all product entities.
 *
 * @Route("/search/laboratory", name="search_laboratory")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product\Search:showlaboratory.html.twig")
 */
public function searchLaboratoryAction(Request $request, $parametro = null) {
    $em = $this->getDoctrine()->getManager();
    $parametro = $this->getRequest()->get("query");
    $affiliated = $em->getRepository('AppBundle:AffiliatedLaboratory')->findByAffiliated($this->getUser());
    $products = $em->getRepository('AppBundle:Laboratory')->findLaboratoryByName($parametro);
    /** @var  $paginator */
    $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate($products, $request->query->get('page', 1), 20);

    return [
        'pagination' => $pagination,
        'usuario' => $this->getUser(),
        'affiliated' => $affiliated
    ];
}

/**
 * Lists all product entities.
 *
 * @Route("/search/laboratory/{id}", name="search_product_laboratory")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product\Search:showproductlaboratory.html.twig")
 */
public function searchLaboratoryProductAction(Request $request, $parametro = null, $id) {
    $em = $this->getDoctrine()->getManager();
    $parametro = $this->getRequest()->get("query");

    $product = $em->getRepository('AppBundle:Product')->findProductByName($parametro);
    $affiliated = $em->getRepository('AppBundle:AffiliatedLaboratory')->findByAffiliated($this->getUser());
    if(sizeof($product) > 0){
        for($x = 0; $x < sizeof($product); $x++){
            $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->findBy(array('product' => $product, 'laboratory' => $id));
        }
        /** @var  $paginator */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($productlaboratory, $request->query->get('page', 1), 40);

        return [
            'affiliated' => $affiliated,
            'id' => $id,
            'pagination' => $pagination,
            'usuario' => $this->getUser()
        ];
    }else{
        $pagination = '';
       return [
            'affiliated' => $affiliated,
            'id' => $id,
            'pagination' => $pagination,
            'usuario' => $this->getUser()
        ]; 
    }
}



/**
 * Displays a form to edit an existing Product entity.
 *
 * @Route("/{id}/edit", name="produtos_show_edit")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:editproduct.html.twig")
 */
public function editPedidoAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Pedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        $editForm = $this->createEditForm($entity);
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'laboratory' => $entity
        );
}

/**
 * Edits an existing Pedido entity.
 *
 * @Route("/{id}", name="produtos_show_edit_update")
 * @Method("PUT")
 * @Template("AppBundle:Backend\Product:/newproduct.html.twig")
 */
public function updateAction(Request $request, $id)
{

    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('AppBundle:Pedido')->find($id);

    if (!$entity) {
        throw $this->createNotFoundException('Unable to find Pedido entity.');
    }

    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);

    if ($editForm->isValid()) {
        try{


          $em->flush();

          $request->getSession()
               ->getFlashBag()
               ->add('success', 'Status atualizado com sucesso!');
        return $this->redirect($this->generateUrl('pedidos_show', array('id' => $id)));

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


    return array(
        'entity'      => $entity,
        'edit_form'   => $editForm->createView(),
    );

}

/**
 * Lists all product.
 *
 * @Route("/shop", name="backend_product_shop")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:list.html.twig")
 */
public function shopAction(Request $request) {
    $em = $this->getDoctrine()->getManager();

    $laboracys = $em->getRepository('AppBundle:ProductLaboratory')->findAllActiveOrderProduct();
    $affiliated = $em->getRepository('AppBundle:AffiliatedLaboratory')->findByAffiliated($this->getUser());
    foreach ($laboracys as $value) {
        $cart = $em->getRepository('AppBundle:Cart')->findBy(array('product' => $value, 'user' => $this->getUser()));
    }
    /** @var  $paginator */
    $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate($laboracys, $request->query->get('page', 1), 40);

    return [
        'affiliated' => $affiliated,
        'carts' => $cart,
        'pagination' => $pagination,
        'usuario' => $this->getUser()
    ];
}

/**
 * Lists all product.
 *
 * @Route("/laboracy", name="backend_product_laboracy")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listlaboratory.html.twig")
 */
public function laboratoryAction() {
    $em = $this->getDoctrine()->getManager();
    $affiliated = $em->getRepository('AppBundle:AffiliatedLaboratory')->findByAffiliated($this->getUser());
    $laboracys = $em->getRepository('AppBundle:Laboratory')->findAllOrderByName();
    return array(
        'affiliated' => $affiliated,
        'laboracys' => $laboracys
    );
}

/**
 * Lists all product.
 *
 * @Route("/laboracy/{id}/products", name="backend_product_laboratory_list")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listlaboratoryproduct.html.twig")
 */
public function laboratoryProductAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();

    $laboracys = $em->getRepository('AppBundle:ProductLaboratory')->findAllActiveByLaboratory($id);

    foreach ($laboracys as $value) {
        $cart = $em->getRepository('AppBundle:Cart')->findBy(array('product' => $value, 'user' => $this->getUser()));
    }
    /** @var  $paginator */
    $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate($laboracys, $request->query->get('page', 1), 40);

    return [
        'id' => $id,
        'carts' => $cart,
        'pagination' => $pagination,
        'usuario' => $this->getUser()
    ];
}

/**
 * Lists all cart product.
 *
 * @Route("/shop/add/{id}/{quantidade}", name="backend_product_shop_add")
 * @Method({"GET","POST"})
 * @Template("AppBundle:Backend\Product:dkete.html.twig")
 */
public function shopAddAction(Request $request, $id, $quantidade){
    //pegando valores do form
    //$products = $request->get("id");
    //$quantproduct = $request->get("quantidade");
    $cart = new Cart();
    $em = $this->getDoctrine()->getManager();
    //buscando prod, pela var order que recebeu o array selecionado e de acordo com a posição
    $prod = $em->getRepository('AppBundle:ProductLaboratory')->findOneById($id);
    //verifica disponibilidade
    if($quantidade > $prod->getQuantidade() || $quantidade == null || !is_numeric($quantidade)){
        
        return $this->redirectToRoute('backend_product_shop');
    }else{
	    //caso tudo esteja ok, insere no carrinho
        $cart->setUser($this->getUser());
        $cart->setProduct($prod);
        $cart->setLaboratory($prod->getLaboratory());
        $cart->setQuantidade($quantidade);
        $cart->setPrice($prod->getCostValue());
        $em->persist($cart);
        //subtraindo da quantidade
        $prod->setQuantidade($prod->getQuantidade() - $quantidade);
        //somatorio de laboratios
        $verifylab = $em->getRepository('AppBundle:ShopLaboratory')->findOneBy(array('laboratory' => $prod->getLaboratory(), 'user' => $this->getUser()));
        //verifica se usuário atual já comprou daquele laboratório
        if($verifylab != null){
            //se sim, faz um adendo ao valor que já existe
            $verifylab->setPrice($verifylab->getPrice() + ($prod->getCostValue() * $quantidade));
        }else{
            //se não, cria do zero
            $shop = new ShopLaboratory();
            $shop->setUser($this->getUser());
            $shop->setPrice($prod->getCostValue() * $quantidade);
            $shop->setLaboratory($prod->getLaboratory());
            $em->persist($shop);
        }
    }
    $em->flush();
    return $this->redirectToRoute('carrinho_show');
}

/**
 * Lists all product cart.
 *
 * @Route("/prefinish/{id}", name="backend_product_laboratory_prefinish")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:prefinish.html.twig")
 */
public function prefinishLaboratoryAction($parametro = null, $id) {
    $em = $this->getDoctrine()->getManager();
    $products = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $id, 'user' => $this->getUser()));
    $lab = $em->getRepository('AppBundle:ShopLaboratory')->findBy(array('laboratory' => $id,'user' => $this->getUser()));
    $parcelas = $em->getRepository('AppBundle:Parcelamento')->findAll();
    $parametro = $this->getRequest()->get("ok");

    $total = 0;
    foreach ($lab as $value) {
      $valor = $value->getPrice();
      $total = $total + $valor;
    }
    return array(
        'id' => $id,
        'parcelas' => $parcelas,
        'parametro' => $parametro,
        'value' => $total,
        'produtos' => $products
    );
}

/**
 * Lists all product cart.
 *
 * @Route("/finish/{id}", name="backend_product_laboratory_finish")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:meuspedidos.html.twig")
 */
public function finishLaboratoryAction(Request $request, $parametro = null,$id) {
    $em = $this->getDoctrine()->getManager();
    $products = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $id,'user' => $this->getUser()));
    $shop = $em->getRepository('AppBundle:ShopLaboratory')->findBy(array('laboratory' => $id,'user' => $this->getUser()));
    $laboratory = $em->getRepository('AppBundle:Laboratory')->find($id);
    $laboratoryagent = $em->getRepository('AppBundle:LaboratoryAgent')->findByLaboratory($id);
    $laboratoryshop = $em->getRepository('AppBundle:LaboratoryShop')->findOneByLaboratory($id);
    $usershop = $em->getRepository('AppBundle:ShopUser')->findOneByUser($this->getUser());
    $total = 0;
    $parametro = $this->getRequest()->get("tipo");
    foreach ($products as $value) {
        $valor = $value->getPrice() * $value->getQuantidade();
        $total = $total + $valor;
    }
    //Instancia um novo objeto de pagamento
    $ok = $em->getRepository('AppBundle:Pedido')->findOneBy(array(),array('name' => 'DESC'));
    if($ok != null){
        $nom = $ok->getName() + 1;
    }else{
        $nom = '1';
    }
    $pedido = new Pedido();
    $pedido->setStatus('EM FATURAMENTO');
    $pedido->setUser($this->getUser());
    //$slug = md5(uniqid(rand(), true));
    $pedido->setName($nom);
    $pedido->setTipo($parametro);
    $pedido->setLaboratory($laboratory);
    $pedido->setPrice($total);
    $em->persist($pedido);
    $em->flush();

    $id = $em->getRepository('AppBundle:Pedido')->findOneByName($nom);
    //Percorre os itens do carrinho para realizar as devidas compras
    foreach ($products as $value) {
      if($value->getProduct() != null){
        $conteudopedido = new ConteudoPedido();
        $conteudopedido->setPedido($id);
        $conteudopedido->setQuantidade($value->getQuantidade());
        $conteudopedido->setUser($this->getUser());
        $conteudopedido->setProduct($value->getProduct());
        $conteudopedido->setLaboratory($value->getProduct()->getLaboratory());
        $em->persist($conteudopedido);
      }
      $em->flush();
    }
    //Em caso de sucesso, remove os objetos atuais do carrinho
    foreach ($products as $value) {
        $em->remove($value);
    }
    foreach ($shop as $value) {
      $em->remove($value);
    }
    $em->flush();
    if($laboratoryshop == null){
        $lab = new LaboratoryShop();
        $lab->setLaboratory($laboratory);
        $em->persist($lab);
        $em->flush();
    }
    if($usershop == null){
       $user = new ShopUser();
       $user->setUser($this->getUser());
       $em->persist($user);
       $em->flush(); 
    }
    //distribuidores
    $first = 'Não tem distribuidor';
    $second = 'Não tem distribuidor';
    $third = 'Não tem distribuidor';
    if($laboratory->getFirstDistributor() != null){
        $first = $laboratory->getFirstDistributor();    
    }
    if($laboratory->getSecondDistributor() != null){
        $second = $laboratory->getSecondDistributor();
    }
    if($laboratory->getThirdDistributor() != null){
         $third = $laboratory->getThirdDistributor();
    }
    $contpedido = $em->getRepository('AppBundle:ConteudoPedido')->findByPedido($id);
    $opcoes = '';
    $total = number_format((float)$total, 2, '.', '');
    foreach($contpedido as $val) {
        $produto = $val->getProduct()->getProduct()->getName();
        $quantidade = $val->getQuantidade();
        $preco = number_format((float)$val->getProduct()->getFactoryValue(), 2, '.', '');
        $desconto = number_format((float)$val->getProduct()->getDiscountValue(), 2, '.', '');
        $precofinal = number_format((float)$val->getProduct()->getCostValue(), 2, '.', '');
        $subtotal = number_format((float)$val->getQuantidade() * $val->getProduct()->getCostValue(), 2, '.', '');

        $opcoes .= "<tr style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>
                    <td style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>".$produto."</td>
                    <td style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>".$quantidade."</td>
                    <td style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>R$ ".$preco."</td>
                    <td style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>".$desconto."%</td>
                    <td style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>R$ ".$precofinal."</td>
                    <td style='padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;'>R$ ".$subtotal."</td>
                    </tr>";
    }
    $this->sendEmail('Lanches - Novo pedido - Número: '.$nom ,$this->getUser()->getUsername(),'
        Caro(a), '.$this->getUser()->getFirstName().'!<br>
        Seu pedido foi recebido e registrado com o número: <b>000'.$nom.'</b>.<br><br>
        <b>Nome: '.$this->getUser()->getFirstName().'</b><br><br>
        <table style="border:1px solid #ccc">
            <thead>
                <tr>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Produto</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;">Quantidade</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;">Preço</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;">Desconto</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Preço Final</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                '.$opcoes.'
                <tr style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;">
                    <td style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;"colspan="6">Total a pagar: R$ '.$total.'</td>
                </tr>
            </tbody>
        </table><br><br>

');
$this->sendEmail('Lanches - Novo pedido - Número: '.$nom,'danielrodriguesdrs331@gmail.com','
        Caro(a), '.$this->getUser()->getFirstName().'!<br>
        Seu pedido foi recebido e registrado com o número: <b>000'.$nom.'</b>.<br><br>
        <b>Nome: '.$this->getUser()->getFirstName().'</b><br><br>
        <table style="border:1px solid #ccc">
            <thead>
                <tr>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Produto</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Quantidade</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Preço</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Desconto</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Preço Final</th>
                    <th style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-top:0;border-bottom-width:2px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                '.$opcoes.'
                <tr style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;">
                    <td style="padding:8px;line-height:1.42857143;border:1px solid #ddd;border-bottom-width:2px;"colspan="6">Total a pagar: R$ '.$total.'</td>
                </tr>
            </tbody>
        </table><br><br>
');
    $request->getSession()
         ->getFlashBag()
         ->add('success', 'Sucesso!');
                return $this->redirectToRoute('pedidos_show');
    }

/**
 * Lists all product cart.
 *
 * @Route("/pedidos", name="pedidos_show")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:meuspedidos.html.twig")
 */
public function pedidosCartAction() {
    $em = $this->getDoctrine()->getManager();
    $laboratory = '';
    $laboratoryname = '';
    $affiliated = '';
    $pedidoslaboratory = '';
    $aff = '';
    if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $aff = $em->getRepository('AppBundle:Pedido')->findAll();
        $pedidoslaboratory = $em->getRepository('AppBundle:Pedido')->findAll();
        $pedidos = $em->getRepository('AppBundle:Pedido')->findAll();

            $laboratory = $em->getRepository('AppBundle:LaboratoryShop')->findAll();

            $affiliated = $em->getRepository('AppBundle:ShopUser')->findAll();

    }else{
        $pedidos = $em->getRepository('AppBundle:Pedido')->findByUser($this->getUser());
        $pedidoslaboratory = $em->getRepository('AppBundle:Pedido')->findByUser($this->getUser());
        $laboratory = $em->getRepository('AppBundle:ShopLaboratory')->findAll();
    }
    return array(
        'aff' => $aff,
        'pedidoslaboratory' => $pedidoslaboratory,
        'laboratoryname' => $laboratoryname,
        'affiliated' => $affiliated,
        'laboratory' => $laboratory,
        'pedidos' => $pedidos
    );
}

/**
 * Lists all product cart.
 *
 * @Route("/pedidos/laboratory/{id}", name="pedidos_show_laboratory")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:meuspedidos.html.twig")
 */
public function pedidosLaboratoryAction($id) {
    $em = $this->getDoctrine()->getManager();
    $laboratory = "";
    $laboratoryname = '';
    $affiliated = '';
    $pedidoslaboratory = '';
    $aff = '';

    if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        $aff = $em->getRepository('AppBundle:Pedido')->findAll();
        $pedidoslaboratory = $em->getRepository('AppBundle:Pedido')->findAll();
        $pedidos = $em->getRepository('AppBundle:Pedido')->findByLaboratory($id);
        foreach ($pedidos as $ped) {
                $laboratory = $em->getRepository('AppBundle:LaboratoryShop')->findAll();
                $affiliated = $em->getRepository('AppBundle:ShopUser')->findAll();
                $laboratoryname = $em->getRepository('AppBundle:Laboratory')->findOneById($ped->getLaboratory());
        }
    }
    else{
        $pedidos = $em->getRepository('AppBundle:Pedido')->findByLaboratoryAndUser($id, $this->getUser());
        $pedidoslaboratory = $em->getRepository('AppBundle:Pedido')->findByUser($this->getUser());
        $laboratory = $em->getRepository('AppBundle:LaboratoryShop')->findAll();
    }

    return array(
        'aff' => $aff,
        'pedidoslaboratory' => $pedidoslaboratory,
        'laboratoryname' => $laboratoryname,
        'affiliated' => $affiliated,
        'laboratory' => $laboratory,
        'pedidos' => $pedidos
    );
}

/**
 * Lists all product cart.
 *
 * @Route("/pedidos/affiliated/{id}", name="pedidos_show_affiliated")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:meuspedidos.html.twig")
 */
public function pedidosAffiliatedAction($id) {
    $laboratoryname = '';
    $laboratory = '';
    $affiliated = '';
    $em = $this->getDoctrine()->getManager();

    $aff = $em->getRepository('AppBundle:Pedido')->findAll();
    $pedidoslaboratory = $em->getRepository('AppBundle:Pedido')->findAll();
    $pedidos = $em->getRepository('AppBundle:Pedido')->findByUser($id);
    foreach ($pedidos as $ped) {
            $laboratory = $em->getRepository('AppBundle:LaboratoryShop')->findAll();
            $laboratoryname = $em->getRepository('AppBundle:ShopUser')->findOneById($ped->getUser());
            $affiliated = $em->getRepository('AppBundle:ShopUser')->findAll();
        }
    return array(
        'aff' => $aff,
        'pedidoslaboratory' => $pedidoslaboratory,
        'laboratoryname' => $laboratoryname,
        'affiliated' => $affiliated,
        'laboratory' => $laboratory,
        'pedidos' => $pedidos
    );
}

/**
 * Lists all product cart.
 *
 * @Route("/pedidos/{id}/produtos", name="produtos_show")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:meusprodutos.html.twig")
 */
public function meusProdutosCartAction($id){
    $em = $this->getDoctrine()->getManager();

    $pedido = $em->getRepository('AppBundle:Pedido')->find($id);

    $conteudos = $em->getRepository('AppBundle:ConteudoPedido')->findByPedido($id);

    return array(
        'conteudos' => $conteudos,
        'pedido' => $pedido
    );
}

/**
 * Lists all cart product.
 *
 * @Route("/shop/remove/all", name="backend_product_shop_remove")
 * @Method({"GET", "POST"})
 * @Template("AppBundle:Backend\Product:listcart.html.twig")
 */
public function shopRemoveAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    $ids = $request->get("id");
    $ids = split(",", $ids);
    foreach ($ids as $id) {
        $product = $em->getRepository('AppBundle:Cart')->find($id);
        $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->find($product->getProduct());
        $shop = $em->getRepository('AppBundle:ShopLaboratory')->findOneBy(array('laboratory' => $productlaboratory->getLaboratory(), 'user' => $this->getUser()));
        $newvalue  = ($shop->getPrice()) - $product->getPrice() * $product->getQuantidade();
        if($product != null){
            $em->remove($product);
            //verifica pra retirar laboratório
            $productremove = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $productlaboratory->getLaboratory(),'user' => $this->getUser()));
            //verifica pra retirar laboratório
            if($productremove == null){
                $em->remove($shop);
            }else{
                $shop->setPrice($newvalue);
                if($newvalue == 0.00){
                    $em->remove($shop);   
                }
            }
            
            if($newvalue != 0.00){
                $em->persist($shop);
            }
            //voltando quantidade
            $productlaboratory->setQuantidade($product->getQuantidade() + $productlaboratory->getQuantidade());
            $em->flush();
        }
    }
    $request->getSession()
        ->getFlashBag()
        ->add('success', 'Item removido com sucesso!');
        return $this->redirectToRoute('carrinho_show');
}

/**
 * Lists all cart product.
 *
 * @Route("/shop/remove/laboratory", name="backend_product_shop_remove_laboratory")
 * @Method({"GET", "POST"})
 * @Template("AppBundle:Backend\Product:listlaboratorycart.html.twig")
 */
public function shopRemoveLaboratoryAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    $ids = $request->get("id");
    $ids = split(",", $ids);
    foreach ($ids as $id) {
        $product = $em->getRepository('AppBundle:Cart')->find($id);
        $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->find($product->getProduct());
        $shop = $em->getRepository('AppBundle:ShopLaboratory')->findOneBy(array('laboratory' => $productlaboratory->getLaboratory(), 'user' => $this->getUser()));
        $newvalue  = ($shop->getPrice()) - $product->getPrice() * $product->getQuantidade();
        if($product != null){
            //verifica pra retirar laboratório
            $em->remove($product);
            $productremove = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $productlaboratory->getLaboratory(),'user' => $this->getUser()));
            //verifica pra retirar laboratório
            if($productremove == null){
                $em->remove($shop);
            }else{
                $shop->setPrice($newvalue);
                if($newvalue == 0.00){
                    $em->remove($shop);   
                }
            }
            
            if($newvalue != 0.00){
                $em->persist($shop);
            }
            //voltando quantidade
            $productlaboratory->setQuantidade($product->getQuantidade() + $productlaboratory->getQuantidade());
            $em->flush();
        }
    }
    $request->getSession()
     ->getFlashBag()
     ->add('success', 'Item removido com sucesso!');
            return $this->redirectToRoute('backend_product_laboratory_listar', array('id'=>$productlaboratory->getLaboratory()->getId()));
}

/**
 * Lists all cart product.
 *
 * @Route("/shop/remove/{id}", name="backend_product_shop_remove_id")
 * @Method({"GET", "POST"})
 * @Template("AppBundle:Backend\Product:list.html.twig")
 */
public function shopRemoveIdAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
        $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->findOneById($id);
        $product = $em->getRepository('AppBundle:Cart')->findOneBy(array('product' => $id, 'user' => $this->getUser()));
        $shop = $em->getRepository('AppBundle:ShopLaboratory')->findOneBy(array('laboratory' => $productlaboratory->getLaboratory(), 'user' => $this->getUser()));
        $newvalue  = ($shop->getPrice()) - $product->getPrice() * $product->getQuantidade();
        if($product != null){
            //verifica pra retirar laboratório
            $em->remove($product);
            $productremove = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $productlaboratory->getLaboratory(),'user' => $this->getUser()));
            //verifica pra retirar laboratório
            if($productremove == null){
                $em->remove($shop);
            }else{
                $shop->setPrice($newvalue);
                if($newvalue == 0.00){
                    $em->remove($shop);   
                }
            }
            
            if($newvalue != 0.00){
                $em->persist($shop);
            }
            //voltando quantidade
            $productlaboratory->setQuantidade($product->getQuantidade() + $productlaboratory->getQuantidade());
            $em->flush();
        }
        return $this->redirectToRoute('backend_product_shop');
}

/**
 * Lists all cart product.
 *
 * @Route("/shop/cancel/{id}", name="backend_product_shop_cancel")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listcart.html.twig")
 */
public function shopCancelAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $id, 'user' => $this->getUser()));
    $shop = $em->getRepository('AppBundle:ShopLaboratory')->findBy(array('laboratory' => $id, 'user' => $this->getUser()));
        foreach ($product as $value) {
            $em->remove($value);
        }
        foreach ($shop as $value) {
            $em->remove($value);
        }

        $em->flush();
        $request->getSession()
             ->getFlashBag()
             ->add('success', 'Item removido com sucesso!');
                    return $this->redirectToRoute('carrinho_show');
    }

/**
 * Lists all cart product.
 *
 * @Route("/cart/shop/remove/{id}/quantidade/{quantidade}", name="backend_product_shop_remove_quantidade")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listcart.html.twig")
 */
public function shopRemoveQuantAction(Request $request, $id, $quantidade) {
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('AppBundle:Cart')->find($id);
    $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->find($product->getProduct());
    $shop = $em->getRepository('AppBundle:ShopLaboratory')->findOneBy(array('laboratory' => $productlaboratory->getLaboratory(), 'user' => $this->getUser()));
    if($quantidade == 0){
        return $this->redirectToRoute('backend_product_shop_remove', array('id'=>$id));
    }else{
        $quantold = $product->getQuantidade();
        $priceold = $product->getPrice();
        $priceold = $priceold * $quantold;
        $oldshop = $shop->getPrice() - $priceold;
        $newvalue  = $productlaboratory->getCostValue() * $quantidade;
        $newvalue = $oldshop + $newvalue;
        $newquant =  $quantidade;
        if($product != null){
            
            $shop->setPrice($newvalue);
            $em->persist($shop);
            $product->setQuantidade($newquant);
            
            $em->persist($product);
            //verifica pra retirar laboratório
            if($newvalue == 0){
                $em->remove($shop);
            }
            $em->flush();
            $request->getSession()
                 ->getFlashBag()
                 ->add('success', 'Quantidade alterada com sucesso!');
                        return $this->redirectToRoute('carrinho_show');
        }
    }
}

/**
 * Lists all cart product.
 *
 * @Route("/cart/shop/remove/{id}/quantidade/{quantidade}/laboratory", name="backend_product_shop_remove_quantidade_laboratory")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listlaboratorycart.html.twig")
 */
public function shopRemoveQuantLaboratoryAction(Request $request, $id, $quantidade) {
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('AppBundle:Cart')->find($id);
    $productlaboratory = $em->getRepository('AppBundle:ProductLaboratory')->find($product->getProduct());
    $shop = $em->getRepository('AppBundle:ShopLaboratory')->findOneBy(array('laboratory' => $productlaboratory->getLaboratory(), 'user' => $this->getUser()));
    if($quantidade == 0){
        return $this->redirectToRoute('backend_product_shop_remove', array('id'=>$id));
    }else{
        $quantold = $product->getQuantidade();
        $priceold = $product->getPrice();
        $priceold = $priceold * $quantold;
        $oldshop = $shop->getPrice() - $priceold;
        $newvalue  = $productlaboratory->getCostValue() * $quantidade;
        $newvalue = $oldshop + $newvalue;
        $newquant =  $quantidade;
        if($product != null){
            
            $shop->setPrice($newvalue);
            $em->persist($shop);
            $product->setQuantidade($newquant);
            
            $em->persist($product);
            //verifica pra retirar laboratório
            if($newvalue == 0){
                $em->remove($shop);
            }
            $em->flush();
            $request->getSession()
                 ->getFlashBag()
                 ->add('success', 'Quantidade alterada com sucesso!');
                        return $this->redirectToRoute('backend_product_laboratory_listar', array('id'=>$productlaboratory->getLaboratory()->getId()));
        }
    }
}

/**
 * Lists all product cart.
 *
 * @Route("/cart", name="carrinho_show")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listcart.html.twig")
 */
public function showCartAction() {
    $em = $this->getDoctrine()->getManager();
    $products = $em->getRepository('AppBundle:Cart')->findByUser($this->getUser());
    $productstotal = $em->getRepository('AppBundle:Cart')->findByUser($this->getUser());
    $total = 0;
    foreach ($productstotal as $value) {
        $total = $total + $value->getPrice() * $value->getQuantidade();
    }

    $shop = $em->getRepository('AppBundle:ShopLaboratory')->findByUser($this->getUser());

    return array(
        'total' => $total,
        'products' => $products,
        'shops' => $shop
    );
}

/**
 * Lists all product cart.
 *
 * @Route("/cart/{id}", name="backend_product_laboratory_listar")
 * @Method("GET")
 * @Template("AppBundle:Backend\Product:listlaboratorycart.html.twig")
 */
public function showCartLaboratoryAction($id) {
    $em = $this->getDoctrine()->getManager();
    $products = $em->getRepository('AppBundle:Cart')->findBy(array('laboratory' => $id, 'user' => $this->getUser()));
    //$productstotal = $em->getRepository('AppBundle:Cart')->findByUser($this->getUser());
    $total = 0;
    foreach ($products as $value) {
        $total = $total + $value->getPrice() * $value->getQuantidade();
    }

    $shop = $em->getRepository('AppBundle:ShopLaboratory')->findBy(array('laboratory' => $id,'user'=>$this->getUser()));

    return array(
        'total' => $total,
        'products' => $products,
        'shops' => $shop
    );
}

/**
 * Creates a new product entity.
 *
 * @Route("/new", name="backend_product_new")
 * @Method({"GET", "POST"})
 * @Template("AppBundle:Backend\Product:new.html.twig")
 */
public function newAction(Request $request) {
    $product = new Product();
    $productlaboratory = new ProductLaboratory();
    $form = $this->createForm('AppBundle\Form\ProductType', $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        // Filtra o array $originalLaboratoryAgentsAction para conter apenas itens que não existam mais
        foreach ($product->getProductlaboratories() as $productLaboratory) {
            //Formatação do preço

            $factoryValue = (str_replace(".", "", $productLaboratory->getFactoryValue()));
            $factoryValue = (str_replace(",", ".", $factoryValue));
            $productLaboratory->setFactoryValue(2.50);
            $productLaboratory->setQuantidade(1000000);
            

            $costValue = (str_replace(".", "", $productLaboratory->getCostValue()));
            $costValue = (str_replace(",", ".", $costValue));
            $costValue = (str_replace("R$", "", $costValue));
            $productLaboratory->setCostValue(2.50);

            $discountValue = (str_replace(".", "", $productLaboratory->getDiscountValue()));
            $discountValue = (str_replace(",", ".", $discountValue));
            $productLaboratory->setDiscountValue(0.00);
            
        }
        $laboratory = $em->getRepository("AppBundle:Laboratory")->findOneById(1);
        $product->setActive(true);


        if($product->getPhoto() == null){
            $product->setPhoto('imagempadrao.jpg');
        }
        $em->persist($product);
        $em->flush($product);

        $productlaboratory->setProduct($product);
        $productlaboratory->setFactoryValue(2.50);
        $productlaboratory->setCostValue(2.50);
        $productlaboratory->setDiscountValue(0.00);
        $productlaboratory->setQuantidade(1000000);
        $productlaboratory->setLaboratory($laboratory);

        $em->persist($productlaboratory);
        $em->flush($productlaboratory);

        $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro criado com sucesso!');

        return $this->redirectToRoute('backend_product_index');
    }

    return array(
        'product' => $product,
        'form' => $form->createView(),
    );
}

/**
 * Displays a form to edit an existing product entity.
 *
 * @Route("/{id}/edit/product", name="backend_product_edit")
 * @Method({"GET", "POST"})
 * @Template("AppBundle:Backend\Product:edit.html.twig")
 */
public function editAction(Request $request, Product $product) {
    $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {

        foreach ($product->getProductlaboratories() as $productLaboratory) {
            //Formatação do preço
            if ($productLaboratory->getFactoryValue() != null) {
                //$factoryValue = (str_replace(".", "", $productLaboratory->getFactoryValue()));
                $factoryValue = (str_replace(",", ".",  $productLaboratory->getFactoryValue()));
                $productLaboratory->setFactoryValue($factoryValue);
                $productLaboratory->setQuantidade(1000000);
            }

            if ($productLaboratory->getCostValue() != null) {
                //$costValue = (str_replace(".", "", $productLaboratory->getCostValue()));
                $costValue = (str_replace(",", ".", $productLaboratory->getCostValue()));
                $costValue = (str_replace("R$", "", $costValue));
                $productLaboratory->setCostValue($costValue);
            }

            if ($productLaboratory->getDiscountValue() != null) {
                //$discountValue = (str_replace(".", "", $productLaboratory->getDiscountValue()));
                $discountValue = (str_replace(",", ".", $productLaboratory->getDiscountValue()));
                $productLaboratory->setDiscountValue($discountValue);
            }
        }
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()
                ->getFlashBag()
                ->add('success', 'Registro atualizado com sucesso!');

        return $this->redirectToRoute('backend_product_edit', array('id' => $product->getId()));
    }

    return array(
        'product' => $product,
        'edit_form' => $editForm->createView(),
    );
}

/**
 * Deletes a product entity.
 *
 * @Route("/{id}/delete", name="backend_product_delete")
 */
public function deleteAction(Request $request, Product $product) {
    $em = $this->getDoctrine()->getManager();
    //$product->unlinkImages();
    $em->remove($product);
    $em->flush($product);

    $request->getSession()
            ->getFlashBag()
            ->add('success', 'Registro excluído com sucesso!');

    return $this->redirectToRoute('backend_product_index');
}

/**
 * Creates a new product entity.
 *
 * @Route("/delete", name="backend_product_delete_post")
 * @Method({"GET", "POST"})
 * @Template()
 */
public function deleteActionPost(Request $request) {
    $ids = $request->get("id");
    $ids = split(",", $ids);
    foreach ($ids as $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository("AppBundle:Product")->find($id);
            //$product->unlinkImages();
            $em->remove($product);
        } catch (\Exception $ex) {
            $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Ocorreu um erro ao excluir os registros.');
            $this->log($ex->getMessage());
            return $this->redirectToRoute('backend_product_index');
        }
    }
    $em->flush();
    $request->getSession()
            ->getFlashBag()
            ->add('success', 'Registro excluído com sucesso!');
    return $this->redirectToRoute('backend_product_index');
}

/**
 * Export the product base.
 *
 * @Route("/export", name="backend_product_export")
 * @Method({"GET", "POST"})
 */
public function exportAction(Request $request) {

    // Cria o objeto PHPExcel
    $produtosExcel = new \PHPExcel();

    // Define propriedades do arquivo
    $produtosExcel->getProperties()->setCreator("Rede Mais")
            ->setTitle("Lista de Produtos");

    $em = $this->getDoctrine()->getManager();

    $productList = $em->getRepository('AppBundle:Product')->findAll();

    // Header da listagem de produtos
    $produtosExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Nome")
            ->setCellValue('B1', "Laboratório")
            ->setCellValue('C1', "Preço de Fábrica")
            ->setCellValue('D1', "Desconto")
            ->setCellValue('E1', "Preço Final");

    // Váriavel com a primeira linha dos produtos
    $row = 2;
    foreach ($productList as $product) {
        foreach ($product->getProductlaboratories() as $productLaboratory) {
            $produtosExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row, $product->getName())
                    ->setCellValue('B' . $row, $productLaboratory->getLaboratory())
                    ->setCellValue('C' . $row, number_format($productLaboratory->getFactoryValue(), 2, ',', '.'))
                    ->setCellValue('D' . $row, number_format($productLaboratory->getCostValue(), 2, ',', '.'))
                    ->setCellValue('E' . $row, number_format($productLaboratory->getDiscountValue(), 2, ',', '.'));
            $row++;
        }
    }

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Produtos.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = \PHPExcel_IOFactory::createWriter($produtosExcel, 'Excel5');
    $objWriter->save('php://output');
}

/**
 * Import a excel base.
 *
 * @Route("/import", name="backend_product_import")
 * @Method({"GET", "POST"})
 * @Template("AppBundle:Backend\Product:import.html.twig")
 */
public function importAction(Request $request) {
    $productImport = new ProductImport();
    $form = $this->createForm('AppBundle\Form\ProductImportType', $productImport);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $em = $this->getDoctrine()->getManager();

        $inputFileName = $productImport->getFile();

        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $this->log('PROCESSANDO O ARQUIVO');

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            //Obtem o registro da linha
            $productRow = $rowData[0];
            if ($productRow[0] != null && $productRow[0] != "" && strtoupper($productRow[0]) != 'PRODUTO') {

                $productList = $em->getRepository('AppBundle:Product')->findProductByName($productRow[0]);
                if (count($productList) > 0) {
                    $product = $productList[0];
                    $productLaboratory = new ProductLaboratory();
                    $productLaboratory->setProduct($product);
                    $productLaboratory->setQuantidade(100000);
                    $productLaboratory->setLaboratory($productImport->getLaboratory());
                    $productLaboratory->setFactoryValue($productRow[1]);
                    $productLaboratory->setDiscountValue($productRow[2] * 100);
                    $productLaboratory->setCostValue($productRow[3]);
                    $em->persist($productLaboratory);
                } else {
                    $product = new Product();
                    $product->setName($productRow[0]);
                    $product->setActive(true);
                    $product->setPhoto('imagempadrao.jpg');
                    $em->persist($product);
                    $productLaboratory = new ProductLaboratory();
                    $productLaboratory->setProduct($product);
                    $productLaboratory->setQuantidade(100000);
                    $productLaboratory->setLaboratory($productImport->getLaboratory());
                    $productLaboratory->setFactoryValue($productRow[1]);
                    $productLaboratory->setDiscountValue($productRow[2] * 100);
                    $productLaboratory->setCostValue($productRow[3]);
                    $em->persist($productLaboratory);
                }
            }
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('success', "Importação realizada com sucesso!");
    }

    return array(
        'productImport' => $productImport,
        'form' => $form->createView(),
    );
}

/**
* Creates a form to edit a Pedido entity.
*
* @param Pedido $entity The entity
*
* @return \Symfony\Component\Form\Form The form
*/
private function createEditForm(Pedido $entity)
{
    $form = $this->createForm(new PedidoType(), $entity, array(
        'action' => $this->generateUrl('produtos_show_edit_update', array('id' => $entity->getId())),
        'method' => 'PUT',
    ));

    $form->add('submit', 'submit', array('label' => 'Update'));

    return $form;
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
