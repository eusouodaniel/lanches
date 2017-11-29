<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * LaboratoryShop
 *
 * @ORM\Table(name="laboratory_shop")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LaboratoryShopRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class LaboratoryShop {

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
     * @ORM\JoinColumn(name="laboratory_id", referencedColumnName="id", nullable=true)
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
     * Set laboratory
     *
     * @param integer $laboratory
     *
     * @return Laboratory
     */
    public function setLaboratory($laboratory)
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    /**
     * Get laboratory
     *
     * @return int
     */
    public function getLaboratory()
    {
        return $this->laboratory;
    }

}
