<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

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
     * @ORM\OneToMany(targetEntity="ProductLaboratory", mappedBy="product", cascade={"all"}, orphanRemoval=true)
     */
    private $productlaboratories;

    public function __construct() {
        $this->productlaboratories = new ArrayCollection();
    }


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
     * @return Product
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
     * Set photo
     *
     * @param string $photo
     *
     * @return Product
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Product
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
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

    public function addProductLaboratory(ProductLaboratory $productLaboratory)
    {
        $productLaboratory->setProduct($this);

        $this->productlaboratories->add($productLaboratory);
    }

    public function removeProductLaboratory(ProductLaboratory $productLaboratory)
    {
        $this->productlaboratories->removeElement($productLaboratory);
    }
    /**
     *
     * @return type
     */
    public function getProductlaboratories() {
        return $this->productlaboratories;
    }

    /**
     *
     */
    public function setProductlaboratories($productlaboratories) {

        $this->productlaboratories = $productlaboratories;

    }

    /**
     * Upload image
     */
    const UPLOAD_PATH_PRODUCT_PHOTO = 'uploads/product/photo';

    /**
     * @Assert\Image(
     *     maxSize = "3072k",
     *     minWidth = 80,
     *     minHeight = 80,
     *     maxSizeMessage = "O tamanho da imagem é muito grande ({{ size }} {{ suffix }}), escolha uma imagem de até {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Formato de arquivo inválido. Formatos permitidos: .gif, .jpeg e .png"
     * )
     */
    private $photoTemp;

    /**
     * Sets photoTemp
     *
     * @param UploadedFile $photoTemp
     */
    public function setPhotoTemp(UploadedFile $photoTemp = null)
    {
        $this->photoTemp = $photoTemp;
    }

    /**
     * Get photoTemp
     *
     * @return UploadedFile
     */
    public function getPhotoTemp()
    {
        return $this->photoTemp;
    }

    /**
     * Unlink Photo (Apagar foto quando excluir objeto)
     */
    public function unlinkImages()
    {
        if ($this->getPhoto() != null) {
            //unlink(Product::UPLOAD_PATH_PRODUCT_PHOTO ."/". $this->getPhoto());
        }
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function uploadImage()
    {

        //Upload de avatar
        if($this->getPhotoTemp()!=null){
          //Se o diretorio não existir, cria
          if (!file_exists(Product::UPLOAD_PATH_PRODUCT_PHOTO)) {
              mkdir(Product::UPLOAD_PATH_PRODUCT_PHOTO, 0755, true);
          }
          if(
              ($this->getPhotoTemp() != $this->getPhoto())
              && (null !== $this->getPhoto())
          ){
              //unlink(Product::UPLOAD_PATH_PRODUCT_PHOTO ."/". $this->getPhoto());
          }

          // Generate a unique name for the file before saving it
          $fileName = md5(uniqid()).'.'.$this->getPhotoTemp()->guessExtension();

          UploadService::compress($this->getPhotoTemp(), Product::UPLOAD_PATH_PRODUCT_PHOTO."/".$fileName, 100);

          // set the path property to the filename where you've saved the file
          $this->photo = $fileName;

          // clean up the file property as you won't need it anymore
          $this->setPhotoTemp(null);
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
