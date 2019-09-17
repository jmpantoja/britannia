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
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UpdatePasswordUseCase implements UseCaseInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;
    /**
     * @var StaffMemberRepositoryInterface
     */
    private $memberRepository;

    public function __construct(EncoderFactoryInterface $encoderFactory, StaffMemberRepositoryInterface $memberRepository)
    {
        $this->encoderFactory = $encoderFactory;
        $this->memberRepository = $memberRepository;
    }

    public function handle(UpdatePassword $command)
    {

        $member = $command->getStaffMember();
        $newPassword = $command->getNewPassword();

        $this->updatePassword($member, $newPassword);

        $this->memberRepository->save($member);
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
