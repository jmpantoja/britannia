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
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends CRUDController
{

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
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
}
