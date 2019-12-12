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

namespace Britannia\Domain\Entity\Mark;


use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

class UnitMark
{

    /**
     * @var UnitMarkId
     */
    private $id;

    /**
     * @var Unit
     */
    private $unit;

    /**
     * @var Student
     */
    private $student;


    /**
     * @var Mark
     */
    private $mark;


    /**
     * @var CarbonImmutable
     */
    private $completedAt;
}
