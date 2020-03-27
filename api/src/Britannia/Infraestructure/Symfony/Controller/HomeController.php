<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Acelaya\Doctrine\Type\PhpEnumType;
use Britannia\Infraestructure\Symfony\Service\Borrame;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function index()
    {
//        $message = (new \Swift_Message('mail de prueba'))
//            ->setFrom('pepe@gmail.com')
//            ->setTo('000.micorreo@gmail.com')
//            ->setBody('parece que si    ');
//        $borrame->send($message);
//
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
