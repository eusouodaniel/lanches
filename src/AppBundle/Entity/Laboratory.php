<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Laboratory
 *
 * @ORM\Table(name="laboratory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LaboratoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Laboratory {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="minimun_value", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $minimunValue;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_requirements", type="string", length=255, nullable=true)
     */
    private $billingRequirements;

    /**
     * @var Distributors
     *
     * @ORM\ManyToOne(targetEntity="Distributors")
     * @ORM\JoinColumn(name="first_distributor", referencedColumnName="id")
     */
    private $firstDistributor;

    /**
     * @var Distributors
     *
     * @ORM\ManyToOne(targetEntity="Distributors")
     * @ORM\JoinColumn(name="second_distributor", referencedColumnName="id")
     */
    private $secondDistributor;

    /**
     * @var Distributors
     *
     * @ORM\ManyToOne(targetEntity="Distributors")
     * @ORM\JoinColumn(name="third_distributor", referencedColumnName="id")
     */
    private $thirdDistributor;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="LaboratoryAgent", mappedBy="laboratory", cascade={"all"}, orphanRemoval=true)
     */
    private $laboratoryagents;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_creation", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $dtCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_update", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $dtUpdate;

    public function __construct() {
        $this->laboratoryagents = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Laboratory
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Laboratory
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * Set minimunValue
     *
     * @param string $minimunValue
     *
     * @return Laboratory
     */
    public function setMinimunValue($minimunValue) {
        $this->minimunValue = $minimunValue;

        return $this;
    }

    /**
     * Get minimunValue
     *
     * @return string
     */
    public function getMinimunValue() {
        return $this->minimunValue;
    }

    /**
     * Set billingRequirements
     *
     * @param string $billingRequirements
     *
     * @return Laboratory
     */
    public function setBillingRequirements($billingRequirements) {
        $this->billingRequirements = $billingRequirements;
        return $this;
    }

    /**
     * Get billingRequirements
     *
     * @return string
     */
    public function getBillingRequirements() {
        return $this->billingRequirements;
    }

    /**
     * Set firstDistributor
     *
     * @param string $firstDistributor
     *
     * @return Laboratory
     */
    public function setFirstDistributor($firstDistributor) {
        $this->firstDistributor = $firstDistributor;
        return $this;
    }

    /**
     * Get firstDistributor
     *
     * @return string
     */
    public function getFirstDistributor() {
        return $this->firstDistributor;
    }

    /**
     * Set secondDistributor
     *
     * @param string $secondDistributor
     *
     * @return Laboratory
     */
    public function setSecondDistributor($secondDistributor) {
        $this->secondDistributor = $secondDistributor;
        return $this;
    }

    /**
     * Get secondDistributor
     *
     * @return string
     */
    public function getSecondDistributor() {
        return $this->secondDistributor;
    }

    /**
     * Set thirdDistributor
     *
     * @param string $thirdDistributor
     *
     * @return Laboratory
     */
    public function setThirdDistributor($thirdDistributor) {
        $this->thirdDistributor = $thirdDistributor;
        return $this;
    }

    /**
     * Get thirdDistributor
     *
     * @return string
     */
    public function getThirdDistributor() {
        return $this->thirdDistributor;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Laboratory
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive() {
        return $this->active;
    }

    public function addLaboratoryAgent(LaboratoryAgent $laboratoryAgent) {
        $laboratoryAgent->setLaboratory($this);

        $this->laboratoryagents->add($laboratoryAgent);
    }

    public function removeLaboratoryAgent(LaboratoryAgent $laboratoryAgent) {
        $this->laboratoryagents->removeElement($laboratoryAgent);
    }

    /**
     *
     * @return type
     */
    public function getLaboratoryagents() {
        return $this->laboratoryagents;
    }

    /**
     *
     */
    public function setLaboratoryagents($laboratoryagents) {

        $this->laboratoryagents = $laboratoryagents;
    }

    /**
     * Set dtCreation
     *
     * @param \DateTime $dtCreation
     *
     * @return Payment
     */
    public function setDtCreation($dtCreation) {
        $this->dtCreation = $dtCreation;

        return $this;
    }

    /**
     * Get dtCreation
     *
     * @return \DateTime
     */
    public function getDtCreation() {
        return $this->dtCreation;
    }

    /**
     * Set dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Payment
     */
    public function setDtUpdate($dtUpdate) {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Get dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate() {
        return $this->dtUpdate;
    }

    /**
     * Upload image
     */
    const UPLOAD_PATH_LABORATORY_AVATAR = 'uploads/laboratory/avatar';

    /**
     * @Assert\Image(
     *     maxSize = "3072k",
     *     minWidth = 75,
     *     minHeight = 75,
     *     maxSizeMessage = "O tamanho da imagem é muito grande ({{ size }} {{ suffix }}), escolha uma imagem de até {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Formato de arquivo inválido. Formatos permitidos: .gif, .jpeg e .png"
     * )
     */
    private $avatarTemp;

    /**
     * Sets avatarTemp
     *
     * @param UploadedFile $avatarTemp
     */
    public function setAvatarTemp(UploadedFile $avatarTemp = null) {
        $this->avatarTemp = $avatarTemp;
    }

    /**
     * Get avatarTemp
     *
     * @return UploadedFile
     */
    public function getAvatarTemp() {
        return $this->avatarTemp;
    }

    /**
     * Unlink Photo (Apagar foto quando excluir objeto)
     */
    public function unlinkImages() {
        if ($this->getAvatar() != null) {
            unlink(Laboratory::UPLOAD_PATH_LABORATORY_AVATAR . "/" . $this->getAvatar());
        }
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function uploadImage() {

//Upload de avatar
        if ($this->getAvatarTemp() != null) {
//Se o diretorio não existir, cria
            if (!file_exists(Laboratory::UPLOAD_PATH_LABORATORY_AVATAR)) {
                mkdir(Laboratory::UPLOAD_PATH_LABORATORY_AVATAR, 0755, true);
            }
            if (
                    ($this->getAvatarTemp() != $this->getAvatar()) && (null !== $this->getAvatar())
            ) {
                unlink(Laboratory::UPLOAD_PATH_LABORATORY_AVATAR . "/" . $this->getAvatar());
            }

// Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $this->getAvatarTemp()->guessExtension();

            UploadService::compress($this->getAvatarTemp(), Laboratory::UPLOAD_PATH_LABORATORY_AVATAR . "/" . $fileName, 100);

// set the path property to the filename where you've saved the file
            $this->avatar = $fileName;

// clean up the file property as you won't need it anymore
            $this->setAvatarTemp(null);
        }
    }

    /**
     * Lifecycle callback to upload the file to the server
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload() {
        $this->uploadImage();
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad() {
        $this->dtUpdate = new \DateTime();
    }

}
