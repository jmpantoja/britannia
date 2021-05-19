<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\School\School;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDDBundle\Sonata\ModelManager;
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
