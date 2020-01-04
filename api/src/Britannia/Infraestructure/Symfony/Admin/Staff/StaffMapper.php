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

namespace Britannia\Infraestructure\Symfony\Admin\Staff;


use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Staff\StaffMemberDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class StaffMapper extends AdminMapper
{

    /**
     * @var EncoderFactoryInterface
     */
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
        parent::__construct();
    }

    protected function className(): string
    {
        return StaffMember::class;
    }

    protected function create(array $values): StaffMember
    {
        $dto = $this->makeDto($values);
        return StaffMember::make($dto);
    }

    /**
     * @param StaffMember $staffMember
     * @param array $values
     */
    protected function update($staffMember, array $values)
    {
        $dto = $this->makeDto($values);
        $staffMember->update($dto);
    }

    /**
     * @param array $values
     * @return StaffMemberDto
     */
    protected function makeDto(array $values): StaffMemberDto
    {
        $values['encoder'] = $this->encoderFactory;
        return StaffMemberDto::fromArray($values);
    }


}
