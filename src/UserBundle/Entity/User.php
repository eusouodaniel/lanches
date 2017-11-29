<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "agent" = "AppBundle\Entity\Agent", "affiliated" = "AppBundle\Entity\Affiliated"})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser implements UserInterface {
    public function __construct() {
        parent::__construct();
    }

    /**
     * __toString method
     */
    public function __toString() {
        return $this->getFullName();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="celphone", type="string", length=255, nullable=true)
     */
    private $celphone;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_creation", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $dtCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_update", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $dtUpdate;

    /**
     * Retorna o nome completo.
     * @return string Nome completo.
     */
    public function getFullName() {
        return $this->getFirstName()." ".$this->getLastName();
    }

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set first_name
     *
     * @param string $first_name
     * @return User
     */
    public function setFirstName($first_name) {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $last_name
     * @return User
     */
    public function setLastName($last_name) {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * Set celphone
     *
     * @param string $celphone
     * @return User
     */
    public function setCelphone($celphone) {
        $this->celphone = $celphone;

        return $this;
    }

    /**
     * Get celphone
     *
     * @return string
     */
    public function getCelphone() {
        return $this->celphone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Get enabled
     *
     * Retorna o Status do Usuário(habilidado,desabilitado)
     *
     */
    public function getStatus()
    {
        $status = $this->enabled;

        if($status == 1)
        {
            $status = "Ativo";
        }
        else
        {
            $status = "Inativo";
        }

        return $status;
    }

    public function getDiscr(){
        return "user";
    }

    /**
     * Set dtCreation
     *
     * @param \DateTime $dtCreation
     *
     * @return Ebook
     */
    public function setDtCreation($dtCreation)
    {
        $this->dtCreation = $dtCreation;

        return $this;
    }

    /**
     * Get dtCreation
     *
     * @return \DateTime
     */
    public function getDtCreation()
    {
        return $this->dtCreation;
    }

    /**
     * Set dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Ebook
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Get dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->dtUpdate = new \DateTime();
    }


}
