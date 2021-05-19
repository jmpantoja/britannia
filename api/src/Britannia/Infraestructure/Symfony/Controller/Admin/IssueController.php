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


use Britannia\Application\UseCase\Issue\ToggleReadState;
use Britannia\Domain\Entity\Issue\Issue;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class IssueController extends CRUDController
{
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }


    public function readAction(Request $request)
    {
        $issue = $this->getIssue($request);

        $command = ToggleReadState::make($issue);
        $this->commandBus->handle($command);

        $this->addFlash('sonata_flash_success', 'Mensaje marcado como leido');
        return new RedirectResponse($this->admin->generateObjectUrl('show', $issue));
    }

    /**
     * @param Request $request
     * @return object|null
     */
    protected function getIssue(Request $request): Issue
    {
        $id = $request->get('id');
        $issue = $this->admin->getSubject();

        if (!$issue) {
            throw new NotFoundHttpException(sprintf('unable to find the issue with id: %s', $id));
        }
        return $issue;
    }
}
