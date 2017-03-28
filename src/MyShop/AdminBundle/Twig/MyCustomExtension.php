<?php

namespace MyShop\AdminBundle\Twig;

class MyCustomExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('bold', [$this, 'showBold'], ['is_safe' => ['html']]),
        );
    }

    public function showBold($data)
    {

        return "<b>" . $data . "</b>";
    }

    public function getName()
    {
        return 'my_custom_ext';
    }
}