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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class PlanningController extends AbstractController
{

    /**
     * @var PlanningService
     */
    private $service;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(PlanningService $service, Security $security)
    {
        $this->service = $service;
        $this->security = $security;
    }

    public function index()
    {
        $this->denyAccessUnlessGranted([
            'ROLE_MANAGER',
            'ROLE_RECEPTION'
        ]);

        $classRooms = $this->service->getClassRooms();

        return $this->render('admin/planning/planning.html.twig', [
            'classRooms' => json_encode($classRooms),
            'events_route' => 'planning_events'
        ]);
    }

    public function custom()
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $classRooms = $this->service->getClassRooms();

        return $this->render('admin/planning/planning.html.twig', [
            'classRooms' => json_encode($classRooms),
            'events_route' => 'my_planning_events'
        ]);
    }


    public function events(Request $request)
    {
        $start = $request->get('start');
        $date = CarbonImmutable::make($start);

        $events = $this->service->getEvents($date);

        return $this->json($events);
    }

    public function customEvents(Request $request)
    {
        $start = $request->get('start');
        $date = CarbonImmutable::make($start);

        $events = $this->service->getEvents($date, $this->security->getUser());

        return $this->json($events);
    }

}
