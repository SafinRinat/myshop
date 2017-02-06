<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\CategoryRepository")
 */
class Category
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\Product", mappedBy="category", cascade={"all"})
     */
    private $productList;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Category", inversedBy="childrenCategories")
     * @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     */
    private $parentCategory;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\Category", mappedBy="parentCategory")
     */
    private $childrenCategories;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->productList = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getProductList()
    {
        return $this->productList;
    }

    /**
     * @param mixed Product
     */
    public function setProductList(Product $productList)
    {
        $this->productList = $productList;
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
     * @return Category
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
     * Add productList
     *
     * @param \MyShop\DefaultBundle\Entity\Product $productList
     *
     * @return Category
     */
    public function addProductList(\MyShop\DefaultBundle\Entity\Product $productList)
    {
        $this->productList[] = $productList;

        return $this;
    }

    /**
     * Remove productList
     *
     * @param \MyShop\DefaultBundle\Entity\Product $productList
     */
    public function removeProductList(\MyShop\DefaultBundle\Entity\Product $productList)
    {
        $this->productList->removeElement($productList);
    }

    /**
     * Set parentCategory
     *
     * @param \MyShop\DefaultBundle\Entity\Category $parentCategory
     *
     * @return Category
     */
    public function setParentCategory(\MyShop\DefaultBundle\Entity\Category $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \MyShop\DefaultBundle\Entity\Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Add childrenCategory
     *
     * @param \MyShop\DefaultBundle\Entity\Category $childrenCategory
     *
     * @return Category
     */
    public function addChildrenCategory(\MyShop\DefaultBundle\Entity\Category $childrenCategory)
    {
        $this->childrenCategories[] = $childrenCategory;

        return $this;
    }

    /**
     * Remove childrenCategory
     *
     * @param \MyShop\DefaultBundle\Entity\Category $childrenCategory
     */
    public function removeChildrenCategory(\MyShop\DefaultBundle\Entity\Category $childrenCategory)
    {
        $this->childrenCategories->removeElement($childrenCategory);
    }

    /**
     * Get childrenCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenCategories()
    {
        return $this->childrenCategories;
    }
}

