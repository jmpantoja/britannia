<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Acelaya\Doctrine\Type\PhpEnumType;
use PlanB\DDD\Domain\VO\CityAddress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function index()
    {

        dump(CityAddress::make(...[
            'city',
            'province'
        ]));


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
