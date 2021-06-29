<?php

namespace Britannia\Infraestructure\Symfony\Controller;


use Carbon\CarbonImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{

    public function index(AdapterInterface $cache)
    {
        $hola = $cache->get('koko', function (ItemInterface $item) {
            $item->expiresAfter(60 * 60 * 24);
            return CarbonImmutable::now();
        });

        //$cache->delete('koko');


        return $this->render('home/index.html.twig', [
            'controller_name' => $hola,
        ]);
    }

}
