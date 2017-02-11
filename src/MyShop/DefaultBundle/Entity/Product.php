<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\ProductRepository")
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
     * @ORM\Column(name="model", type="string", length=255, unique=true)
     */
    private $model;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created_at", type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer", nullable=true)
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_stock_start", type="datetime", nullable=true)
     */
    private $dateStockStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_stock_end", type="datetime", nullable=true)
     */
    private $dateStockEnd;

    /**
     * @var boolean
     *
     * @ORM\Column(name="stock_status", type="boolean", length=64, nullable=true)
     */
    private $stockStatus;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Category", inversedBy="productList")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\ProductPhoto", mappedBy="product")
     */
    private $photos;

    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setDateCreatedAt($date);

        $this->photos = new ArrayCollection();
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * Set model
     *
     * @param string $model
     *
     * @return Product
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return Product
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return Product
     */
    public function setDateCreatedAt($dateCreatedAt)
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    /**
     * Get dateCreatedAt
     *
     * @return \DateTime
     */
    public function getDateCreatedAt()
    {
        return $this->dateCreatedAt;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return Product
     */
    public function setcount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getcount()
    {
        return $this->count;
    }

    /**
     * Set dateStockStart
     *
     * @param \DateTime $dateStockStart
     *
     * @return Product
     */
    public function setDateStockStart($dateStockStart)
    {
        $this->dateStockStart = $dateStockStart;

        return $this;
    }

    /**
     * Get dateStockStart
     *
     * @return \DateTime
     */
    public function getDateStockStart()
    {
        return $this->dateStockStart;
    }

    /**
     * Set dateStockEnd
     *
     * @param \DateTime $dateStockEnd
     *
     * @return Product
     */
    public function setDateStockEnd($dateStockEnd)
    {
        $this->dateStockEnd = $dateStockEnd;

        return $this;
    }

    /**
     * Get dateStockEnd
     *
     * @return \DateTime
     */
    public function getDateStockEnd()
    {
        return $this->dateStockEnd;
    }

    /**
     * Set stockStatus
     *
     * @param boolean $stockStatus
     *
     * @return Product
     */
    public function setStockStatus($stockStatus)
    {
        $this->stockStatus = $stockStatus;

        return $this;
    }

    /**
     * Get stockStatus
     *
     * @return boolean
     */
    public function getStockStatus()
    {
        return $this->stockStatus;
    }
    /**
     * Add photo
     *
     * @param \MyShop\DefaultBundle\Entity\ProductPhoto $photo
     *
     * @return Product
     */
    public function addPhoto(\MyShop\DefaultBundle\Entity\ProductPhoto $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \MyShop\DefaultBundle\Entity\ProductPhoto $photo
     */
    public function removePhoto(\MyShop\DefaultBundle\Entity\ProductPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}

