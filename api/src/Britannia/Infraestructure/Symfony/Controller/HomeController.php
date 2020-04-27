<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Acelaya\Doctrine\Type\PhpEnumType;
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Britannia\Infraestructure\Symfony\Service\Borrame;
use Britannia\Infraestructure\Symfony\Service\Staff\StaffMemberCoursesOrganizer;
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
