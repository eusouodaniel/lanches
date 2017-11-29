<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LaboratoryAgent
 *
 * @ORM\Table(name="laboratory_agent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LaboratoryAgentRepository")
 */
class LaboratoryAgent
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
     * @var Agent
     *
     * @ORM\ManyToOne(targetEntity="Agent")
     * @ORM\JoinColumn(name="agent_id", referencedColumnName="id")
     */
    private $agent;

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
     * @return LaboratoryAgent
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
     * Set agent
     *
     * @param Agent $agent
     *
     * @return LaboratoryAgent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }
}
