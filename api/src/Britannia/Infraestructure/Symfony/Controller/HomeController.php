<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Acelaya\Doctrine\Type\PhpEnumType;
use Britannia\Infraestructure\Symfony\Service\Borrame;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{

    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
