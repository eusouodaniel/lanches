<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LaboratoryAgent
 *
 * @ORM\Table(name="affiliated_laboratory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AffiliatedLaboratoryRepository")
 */
class AffiliatedLaboratory
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
     * @ORM\ManyToOne(targetEntity="Laboratory", inversedBy="laboratoryagents")
     * @ORM\JoinColumn(name="laboratory_id", referencedColumnName="id")
     */
    private $laboratory;

    /**
     * @var Affiliated
     *
     * @ORM\ManyToOne(targetEntity="Affiliated")
     * @ORM\JoinColumn(name="affiliated_id", referencedColumnName="id")
     */
    private $affiliated;

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
     * @param Laboratory $laboratory
     *
     * @return AffiliatedLaboratory
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
     * Set affiliated
     *
     * @param Affiliated $affiliated
     *
     * @return AffiliatedLaboratory
     */
    public function setAffiliated($affiliated)
    {
        $this->affiliated = $affiliated;

        return $this;
    }

    /**
     * Get affiliated
     *
     * @return Agent
     */
    public function getAffiliated()
    {
        return $this->affiliated;
    }
}
