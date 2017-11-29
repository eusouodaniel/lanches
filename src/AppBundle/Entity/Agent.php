<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Agent
 *
 * @ORM\Table(name="agent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgentRepository")
 */
class Agent extends User {

    public function __construct() {
        parent::__construct();
        $this->agentregions = new ArrayCollection();
        $this->laboratoryagents = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $perfumery;

    /**
     * @ORM\OneToMany(targetEntity="AgentRegion", mappedBy="agent", cascade={"all"}, orphanRemoval=true)
     */
    private $agentregions;

    /**
     * @ORM\OneToMany(targetEntity="LaboratoryAgent", mappedBy="agent", cascade={"all"}, orphanRemoval=true)
     */
    private $laboratoryagents;

    public function getDiscr(){
        return "agent";
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function addAgentRegion(AgentRegion $agentRegion) {
        $agentRegion->setAgent($this);

        $this->agentregions->add($agentRegion);
    }

    public function removeAgentRegion(AgentRegion $agentRegion) {
        $this->agentregions->removeElement($agentRegion);
    }

    /**
     *
     * @return type
     */
    public function getAgentregions() {
        return $this->agentregions;
    }

    /**
     *
     */
    public function setAgentregions($agentregions) {
        $this->agentregions = $agentregions;
    }

    public function setLaboratoryagents($laboratoryagents) {
        $this->laboratoryagents = $laboratoryagents;
        return $this;
    }

    /**
     * @return type
     */
    public function getLaboratoryagents() {
        return $this->laboratoryagents;
    }

    /**
     *
     * @return type
     */
    public function getPerfumery() {
        return $this->perfumery;
    }

    /**
     *
     */
    public function setPerfumery($perfumery) {
        $this->perfumery = $perfumery;
    }

}
