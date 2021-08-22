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


use Britannia\Application\UseCase\Calendar\ChangeWorkDayStatus;
use Britannia\Infraestructure\Symfony\Service\Calendar\HolidayService;
use Carbon\CarbonImmutable;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CalendarController extends CRUDController
{
    /**
     * @var HolidayService
     */
    private HolidayService $holidayService;

    /**
     * @var CommandBus
     */
    private $commandBus;


    public function __construct(HolidayService $holidayService, CommandBus $commandBus)
    {

        $this->holidayService = $holidayService;
        $this->commandBus = $commandBus;
    }

    public function listAction()
    {
        return $this->render('admin/calendar/calendar.html.twig', [
            'calendar_endpoint' => 'admin_britannia_domain_calendar_calendar_calendar.info',
            'change_status_action' => 'admin_britannia_domain_calendar_calendar_calendar.change_status'
        ]);
    }

    public function infoAction()
    {
        $this->admin->checkAccess('list');

        $start = $this->getStart();
        $end = $this->getEnd();

        $holidays = $this->holidayService->range($start, $end);

        return $this->renderJson($holidays);
    }

    public function changeStatusAction()
    {
        $this->admin->checkAccess('edit');

        $toHoliday = $this->getHoliday();
        $start = $this->getStart();
        $end = $this->getEnd()->subDay();

        $command = ChangeWorkDayStatus::toWorkday($start, $end);

        if($toHoliday){
            $command = ChangeWorkDayStatus::toHoliday($start, $end);
        }

        $this->commandBus->handle($command);

        return $this->renderJson([
            'start' => $this->getStart(),
            'end' => $this->getEnd(),
            'holiday' => $this->getHoliday(),
        ]);
    }

    public function batchActionToHoliday(ProxyQueryInterface $selectedModelQuery)
    {
        $this->admin->checkAccess('edit');

        $command = ChangeWorkDayStatus::toHoliday($selectedModelQuery->execute());
        $this->commandBus->handle($command);


        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }

    public function batchActionToWorkday(ProxyQueryInterface $selectedModelQuery)
    {
        $this->admin->checkAccess('edit');

        $command = ChangeWorkDayStatus::toWorkday($selectedModelQuery->execute());
        $this->commandBus->handle($command);

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }

    /**
     * @return CarbonImmutable|null
     */
    private function getStart(): CarbonImmutable
    {
        return CarbonImmutable::make($this->getRequest()->get('start'));
    }

    /**
     * @return CarbonImmutable|null
     */
    private function getEnd(): CarbonImmutable
    {
        return CarbonImmutable::make($this->getRequest()->get('end'));
    }

    private function getHoliday(): bool
    {
        $holiday = $this->getRequest()->get('holiday');

        return $holiday === 'true';
    }
}
