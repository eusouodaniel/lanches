<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConteudoPedido
 *
 * @ORM\Table(name="conteudo_pedido")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConteudoPedidoRepository")
 */
class ConteudoPedido
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ConteudoPedido
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="cartProducts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var ConteudoPedido
     *
     * @ORM\ManyToOne(targetEntity="Pedido")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id", nullable=true)
     */
    private $pedido;

    /**
     * @var int
     *
     * @ORM\Column(name="quantidade", type="integer", nullable=true)
     */
    private $quantidade;

    /**
     * @var ProductLaboratory
     *
     * @ORM\ManyToOne(targetEntity="ProductLaboratory")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $product;

    /**
     * @var Laboratory
     *
     * @ORM\ManyToOne(targetEntity="Laboratory", inversedBy="laboratoryagents")
     * @ORM\JoinColumn(name="laboratory_id", referencedColumnName="id")
     */
    private $laboratory;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Cart
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return ConteudoPedido
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return int
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set pedido
     *
     * @param integer $pedido
     *
     * @return Pedido
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return int
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set product
     *
     * @param integer $product
     *
     * @return ProductLaboratory
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return int
     */
    public function getProduct()
    {
        return $this->product;
    }



    /**
     * Set price
     *
     * @param string $price
     *
     * @return Pedido
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }


    /**
     * Get price
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set laboratory
     *
     * @param Laboratory $laboratory
     *
     * @return ConteudoPedido
     */
    public function setLaboratory($laboratory)
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    /**
     * Get laboratory
     *
     * @return Laboratory
     */
    public function getLaboratory()
    {
        return $this->laboratory;
    }
}

