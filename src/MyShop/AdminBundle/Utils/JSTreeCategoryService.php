<?php
namespace MyShop\AdminBundle\Utils;

class JSTreeCategoryService
{
    private $categoryList;
    /*
     * @var string;
     * */
    private $repositoryName;

    /*
     * @var \Doctrine\Bundle\DoctrineBundle\Registry;
     * */
    private $doctrine;

    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine, $repositoryName)
    {
        $this->doctrine = $doctrine;
        $this->repositoryName = $repositoryName;
    }

    public function getCategoryListJSON()
    {
        $this->categoryList = $this->doctrine->getRepository($this->repositoryName)->findAll();

        $results = [];
        /** @var Category $cat */
        foreach ($this->categoryList as $cat)
        {
            $id_parent = $cat->getParentCategory() !== null ? $cat->getParentCategory()->getId() : "#";
            $results[] = [
                "id" => $cat->getId(),
                "parent" => $id_parent,
                "text" => $cat->getName() . "<a class='category category_remove glyphicon glyphicon-trash' href='/admin/category/deleteAjax/" .  $cat->getId() . "'></a><a class='category category_edit glyphicon glyphicon-pencil' href='/admin/category/edit/" . $cat->getId() . "'></a>",
                "category_remove" => "<a class='category category_remove glyphicon glyphicon-trash' href='/admin/category/deleteAjax/" .  $cat->getId() . "'></a>",
                "category_edit" => "<a class='category category_edit glyphicon glyphicon-pencil' href='/admin/category/edit/" . $cat->getId() . "'></a>",
                "a_attr" => ["id" => "category_" . $cat->getId()],
            ];
        }
        return json_encode($results);
    }
}