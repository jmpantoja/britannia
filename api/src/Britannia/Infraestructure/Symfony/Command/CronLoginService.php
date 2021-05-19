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

namespace Britannia\Infraestructure\Symfony\Command;


use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class CronLoginService implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var StaffMemberRepositoryInterface
     */
    private StaffMemberRepositoryInterface $userRepository;

    public function __construct(ContainerInterface $container, StaffMemberRepositoryInterface $userRepository)
    {
        $this->container = $container;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function login()
    {
        $user = $this->userRepository->findOneBy([
            'userName' => 'administrador'
        ]);

        if (empty($user)) {
            return;
        }

        $token = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }
}
