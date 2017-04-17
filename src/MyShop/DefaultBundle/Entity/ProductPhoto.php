<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductPhoto
 *
 * @ORM\Table(name="product_photo")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\ProductPhotoRepository")
 */
class ProductPhoto
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, unique=true)
     */
    private $fileName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created_at", type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="main_file_name", type="string", length=255)
     */
    private $mainFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_file_name", type="string", length=255)
     */
    private $mobileFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb_file_name", type="string", length=255)
     */
    private $thumbFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="basket_file_name", type="string", length=255)
     */
    private $basketFileName;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Product", inversedBy="photos")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setDateCreatedAt($date);
    }

    /**
     * @return \DateTime
     */
    public function getDateCreatedAt()
    {
        return $this->dateCreatedAt;
    }

    /**
     * @param \DateTime $dateCreatedAt
     */
    public function setDateCreatedAt($dateCreatedAt)
    {
        $this->dateCreatedAt = $dateCreatedAt;
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
     * Set title
     *
     * @param string $title
     *
     * @return ProductPhoto
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return ProductPhoto
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set product
     *
     * @param \MyShop\DefaultBundle\Entity\Product $product
     *
     * @return ProductPhoto
     */
    public function setProduct(\MyShop\DefaultBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \MyShop\DefaultBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return string
     */
    public function getMainFileName()
    {
        return $this->mainFileName;
    }

    /**
     * @param string $mainFileName
     */
    public function setMainFileName($mainFileName)
    {
        $this->mainFileName = $mainFileName;
    }

    /**
     * @return string
     */
    public function getMobileFileName()
    {
        return $this->mobileFileName;
    }

    /**
     * @param string $mobileFileName
     */
    public function setMobileFileName($mobileFileName)
    {
        $this->mobileFileName = $mobileFileName;
    }

    /**
     * @return string
     */
    public function getThumbFileName()
    {
        return $this->thumbFileName;
    }

    /**
     * @param string $thumbFileName
     */
    public function setThumbFileName($thumbFileName)
    {
        $this->thumbFileName = $thumbFileName;
    }

    /**
     * @return string
     */
    public function getBasketFileName()
    {
        return $this->basketFileName;
    }

    /**
     * @param string $basketFileName
     */
    public function setBasketFileName($basketFileName)
    {
        $this->basketFileName = $basketFileName;
    }

    /**
     * @return array
     */
    public function getUnlinkNames()
    {
        return $names = [
            $this->fileName,
            $this->mobileFileName,
            $this->mainFileName,
            $this->thumbFileName,
            $this->basketFileName
        ];
    }
}

