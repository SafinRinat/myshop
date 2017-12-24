<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\CssSelector\Parser\Reader;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $session = $this->get("session");
        $viewData = [];
        $viewData["history"] = "";
        if ($session->has("history")) {
            $viewData["history"] = $session->get("history");
        }
        return $this->render("MyShopAdminBundle:Default:index.html.twig", [
//            "history" => $viewData["history"]
        ]);
    }

    /**
     * @Route("/homework")
     * @return $this
    */
    public function homeWorkAction() {
        return $this->render("@MyShopAdmin/homework/homework.html.twig");
    }
}
