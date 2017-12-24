<?php

namespace MyShop\AdminBundle\Twig;

/**
 * Class MyCustomExtension
 * @package MyShop\AdminBundle\Twig
 * link http://symfony.com/doc/current/templating/twig_extension.html
 */
class MyCustomExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('bold', [$this, 'showBold'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('red', [$this, 'showRed'], ['is_safe' => ['html']]),
        );
    }

    public function showBold($data)
    {

        return "<b>" . $data . "</b>";
    }

    public function showRed($data)
    {
        return "<span style='color: red'>" . $data . "</span>";
    }

    public function getName()
    {
        return 'my_custom_ext';
    }
}