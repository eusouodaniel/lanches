<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductLaboratory
 *
 * @ORM\Table(name="product_laboratory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductLaboratoryRepository")
 */
class ProductLaboratory
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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
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
     * @var string
     *
     * @ORM\Column(name="factory_value", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $factoryValue;

    /**
     * @var string
     *
     * @ORM\Column(name="cost_value", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $costValue;

    /**
     * @var string
     *
     * @ORM\Column(name="discount_value", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discountValue;

    /**
     * @var int
     *
     * @ORM\Column(name="quantidade", type="integer", nullable=true)
     */
    private $quantidade;

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
     * Set product
     *
     * @param Product $product
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
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set laboratory
     *
     * @param Laboratory $laboratory
     *
     * @return ProductLaboratory
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

    /**
     * Set factoryValue
     *
     * @param string $factoryValue
     *
     * @return Laboratory
     */
    public function setFactoryValue($factoryValue)
    {
        $this->factoryValue = $factoryValue;

        return $this;
    }

    /**
     * Get factoryValue
     *
     * @return string
     */
    public function getFactoryValue()
    {
        return $this->factoryValue;
    }

    /**
     * Set costValue
     *
     * @param string $costValue
     *
     * @return Laboratory
     */
    public function setCostValue($costValue)
    {
        $this->costValue = $costValue;

        return $this;
    }

    /**
     * Get costValue
     *
     * @return string
     */
    public function getCostValue()
    {
        return $this->costValue;
    }

    /**
     * Set discountValue
     *
     * @param string $discountValue
     *
     * @return Laboratory
     */
    public function setDiscountValue($discountValue)
    {
        $this->discountValue = $discountValue;

        return $this;
    }

    /**
     * Get discountValue
     *
     * @return string
     */
    public function getDiscountValue()
    {
        return $this->discountValue;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return Cart
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

}
