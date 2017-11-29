<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Affiliated
 *
 * @ORM\Table(name="affiliated")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AffiliatedRepository")
 */
class Affiliated extends User {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    public function getDiscr(){
        return "affiliated";
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }
}
