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

namespace Britannia\Domain\Entity\Staff;


use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\VO\StaffMember\Status;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PostalAddress;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;


final class StaffMemberDto extends Dto
{
    public int $oldId;

    public ?string $userName;
    public ?DNI $dni;
    public ?string $password;
    public ?FullName $fullName;
    public ?PostalAddress $address = null;
    public array $emails = [];
    public array $phoneNumbers = [];
    public CourseList $courses;
    public ?Photo $photo = null;
    public ?Status $status = null;
    public ?string $comment = null;
    public ?array $roles;
    public EncoderFactory $encoder;

    protected function defaults(): array
    {
        return [
            'courses' => CourseList::collect(),
            'status'=>Status::NON_PERMANENT()
        ];
    }
}
