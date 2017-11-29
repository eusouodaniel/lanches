<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Cotacoes
 *
 * @ORM\Table(name="cotacoes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CotacoesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Cotacoes
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="archive", type="string", length=255, nullable=true)
     */
    private $archive;

    /**
     * @var Cotacoes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Affiliated")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

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
     * Set name
     *
     * @param string $name
     *
     * @return Cotacoes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set archive
     *
     * @param string $archive
     *
     * @return Cotacoes
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return string
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Cotacoes
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
     * Set dtCreation
     *
     * @param \DateTime $dtCreation
     *
     * @return Payment
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
     * @return Payment
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
     * Upload image
     */
    const UPLOAD_PATH_PRODUCT_PHOTO = 'uploads/perfumaria/cotacoes';

    /**
     * @Assert\File(
     *     maxSize = "9072k",
     *     maxSizeMessage = "O tamanho do arquivo é muito grande ({{ size }} {{ suffix }}), escolha um arquivo de até {{ limit }} {{ suffix }}",
     *     mimeTypes = {"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     *     mimeTypesMessage = "Formato de arquivo inválido. Formatos permitidos: .xlsx"
     * )
     */
    private $archiveTemp;

    /**
     * Sets archiveTemp
     *
     * @param UploadedFile $archiveTemp
     */
    public function setArchiveTemp(UploadedFile $archiveTemp = null)
    {
        $this->archiveTemp = $archiveTemp;
    }

    /**
     * Get archiveTemp
     *
     * @return UploadedFile
     */
    public function getArchiveTemp()
    {
        return $this->archiveTemp;
    }

    /**
     * Unlink Photo (Apagar foto quando excluir objeto)
     */
    public function unlinkImages()
    {
        if ($this->getArchive() != null) {
            unlink(Cotacoes::UPLOAD_PATH_PRODUCT_PHOTO ."/". $this->getArchive());
        }
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function uploadImage()
    {

        //Upload de avatar
        if($this->getArchiveTemp()!=null){
          //Se o diretorio não existir, cria
          if (!file_exists(Cotacoes::UPLOAD_PATH_PRODUCT_PHOTO)) {
              mkdir(Cotacoes::UPLOAD_PATH_PRODUCT_PHOTO, 0755, true);
          }
          if(
              ($this->getArchiveTemp() != $this->getArchive())
              && (null !== $this->getArchive())
          ){
              unlink(Cotacoes::UPLOAD_PATH_PRODUCT_PHOTO ."/". $this->getArchive());
          }

          // Generate a unique name for the file before saving it
          $fileName = md5(uniqid()).'.'.$this->getArchiveTemp()->guessExtension();

          UploadService::upload($this->getArchiveTemp(), Cotacoes::UPLOAD_PATH_PRODUCT_PHOTO."/".$fileName, 100);

          // set the path property to the filename where you've saved the file
          $this->archive = $fileName;

          // clean up the file property as you won't need it anymore
          $this->setArchiveTemp(null);
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
    public function postLoad()
    {
        $this->dtUpdate = new \DateTime();
    }
}