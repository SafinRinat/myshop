<?php

namespace MyShop\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        //on Symfony 3.3+ use arguments: Request $request, AuthenticationUtils $authUtils
        $authUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render("MyShopAdminBundle:security:login.html.twig", [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }
}