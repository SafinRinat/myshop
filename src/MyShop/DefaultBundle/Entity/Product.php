<?php

namespace MyShop\DefaultBundle\Entity;

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
     * @var int
     *
     * @ORM\Column(name="product_code", type="integer", unique=true)
     */
    private $productCode;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text")
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
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
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_stock_begin", type="datetime")
     */
    private $dateStockBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_stock_end", type="datetime")
     */
    private $dateStockEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="stock_status", type="string", length=128)
     */
    private $stockStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_offer_end", type="datetime")
     */
    private $dateOfferEnd;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Category", inversedBy="productList")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
    */
    private $category;


    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setDateCreatedAt($date);
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
     * Set productCode
     *
     * @param integer $productCode
     *
     * @return Product
     */
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;

        return $this;
    }

    /**
     * Get productCode
     *
     * @return int
     */
    public function getProductCode()
    {
        return $this->productCode;
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
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set dateStockBegin
     *
     * @param \DateTime $dateStockBegin
     *
     * @return Product
     */
    public function setDateStockBegin($dateStockBegin)
    {
        $this->dateStockBegin = $dateStockBegin;

        return $this;
    }

    /**
     * Get dateStockBegin
     *
     * @return \DateTime
     */
    public function getDateStockBegin()
    {
        return $this->dateStockBegin;
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
     * @param string $stockStatus
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
     * @return string
     */
    public function getStockStatus()
    {
        return $this->stockStatus;
    }

    /**
     * Set dateOfferEnd
     *
     * @param \DateTime $dateOfferEnd
     *
     * @return Product
     */
    public function setDateOfferEnd($dateOfferEnd)
    {
        $this->dateOfferEnd = $dateOfferEnd;

        return $this;
    }

    /**
     * Get dateOfferEnd
     *
     * @return \DateTime
     */
    public function getDateOfferEnd()
    {
        return $this->dateOfferEnd;
    }
}

