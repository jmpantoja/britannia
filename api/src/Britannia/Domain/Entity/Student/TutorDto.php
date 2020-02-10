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

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\VO\Student\Job\Job;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PostalAddress;

final class TutorDto extends Dto
{
    public ?FullName $fullName;
    public ?DNI $dni;
    public ?PostalAddress $address;
    public array $emails;
    public array $phoneNumbers;
    public ?Job $job;
}


