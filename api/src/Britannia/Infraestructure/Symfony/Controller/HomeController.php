<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\School\School;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDDBundle\Sonata\ModelManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ModelManager
     */
    private ModelManager $modelManager;

    public function __construct(EntityManagerInterface $entityManager, ModelManager $modelManager)
    {
        $this->entityManager = $entityManager;

        $this->modelManager = $modelManager;
    }


    public function index()
    {

        $aaa = $this->entityManager->createQueryBuilder()
            ->from(School::class, 'A')
            ->select('A')
            ->setCacheable(true)
            ->getQuery()
            ->execute();

//        $query = $this->modelManager->createQuery(School::class, 'o');

        dump($aaa);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
