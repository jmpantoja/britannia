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

namespace Britannia\Application\UseCase\StaffMember;


use Britannia\Domain\Entity\Staff\StaffMember;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UpdatePasswordUseCase implements UseCaseInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function handle(UpdatePassword $command)
    {

        $member = $command->getStaffMember();
        $newPassword = $command->getNewPassword();

        $this->updatePassword($member, $newPassword);
    }

    /**
     * @param $member
     */
    private function updatePassword(StaffMember $member, string $newPassword): void
    {
        $encoder = $this->encoderFactory->getEncoder($member);

        $salt = $member->getSalt();
        $encodedPassword = $encoder->encodePassword($newPassword, $salt);

        $member->setPassword($encodedPassword);
    }

}
