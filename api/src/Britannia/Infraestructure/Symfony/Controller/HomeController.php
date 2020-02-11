<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Acelaya\Doctrine\Type\PhpEnumType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function index()
    {

        return $this->render('home/calendar.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
