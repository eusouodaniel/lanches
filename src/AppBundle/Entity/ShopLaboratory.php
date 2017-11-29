<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ShopLaboratory
 *
 * @ORM\Table(name="shoplaboratory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShopLaboratoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ShopLaboratory
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
    * @var Laboratory
    *
    * @ORM\ManyToOne(targetEntity="Laboratory")
    * @ORM\JoinColumn(name="laboratory_id", referencedColumnName="id")
    */
    protected $laboratory;

    /**
     * @var ShopLaboratory
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

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
     * Set laboratory
     *
     * @param int $laboratory
     *
     * @return ShopLaboratory
     */
    public function setLaboratory($laboratory) {
        $this->laboratory = $laboratory;

        return $this;
    }

    /**
     * Get laboratory
     *
     * @return string
     */
    public function getLaboratory() {
        return $this->laboratory;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return ShopLaboratory
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
     * Set price
     *
     * @param string $price
     *
     * @return ShopLaboratory
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

}
