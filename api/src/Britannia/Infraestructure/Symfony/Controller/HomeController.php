<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use PlanB\DDD\Domain\Event\DomainEventCollectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function index()
    {



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
