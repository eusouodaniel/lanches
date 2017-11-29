<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgentRegion
 *
 * @ORM\Table(name="agent_region")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgentRegionRepository")
 */
class AgentRegion
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
     * @var Agent
     *
     * @ORM\ManyToOne(targetEntity="Agent", inversedBy="agentregions")
     * @ORM\JoinColumn(name="agent_id", referencedColumnName="id")
     */
    private $agent;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;

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
     * Set agent
     *
     * @param Agent $agent
     *
     * @return AgentRegion
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

    /**
     * Set region
     *
     * @param Region $region
     *
     * @return AgentRegion
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }
}
