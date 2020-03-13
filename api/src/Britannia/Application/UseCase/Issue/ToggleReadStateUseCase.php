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

namespace Britannia\Application\UseCase\Issue;


use PlanB\DDD\Application\UseCase\UseCaseInterface;
use Symfony\Component\Security\Core\Security;

final class ToggleReadStateUseCase implements UseCaseInterface
{
    /**
     * @var Security
     */
    private Security $security;


    /**
     * ToggleReadStateUseCase constructor.
     */
    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    public function handle(ToggleReadState $command)
    {
        $user = $this->security->getUser();

        $issue = $command->issue();
        $issue->toggleReadStateByUser($user);
    }
}
