<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Controller\Admin;


use Britannia\Infraestructure\Symfony\Service\Planning\PlanningService;
use Carbon\CarbonImmutable;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PlanningController extends AbstractController
{

    /**
     * @var PlanningService
     */
    private $service;
    /**
     * @var Pdf
     */
    private $pdf;

    public function __construct(PlanningService $service, Pdf $pdf)
    {
        $this->service = $service;
        $this->pdf = $pdf;
    }

    public function index()
    {
        $classRooms = $this->service->getClassRooms();

        return  $this->render('admin/occupation/index.html.twig', [
            'classRooms' => json_encode($classRooms),
            'events_route' => 'planning_events'
        ]);

//        $html = $this->renderView('admin/occupation/borrame.html.twig', [
//        ]);
//
//        $filename = sprintf('specifications-%s.pdf', date('Y-m-d-hh-ss'));
//        return new Response(
//            $this->pdf->getOutputFromHtml($html),
//            200,
//            [
//                'Content-Type' => 'application/pdf',
//                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
//            ]
//        );
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $date = CarbonImmutable::make($start);

        $events = $this->service->getEvents($date);

        return $this->json($events);
    }


}
