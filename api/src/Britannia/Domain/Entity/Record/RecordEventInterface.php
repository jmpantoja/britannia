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

namespace Britannia\Domain\Entity\Record;


use Britannia\Domain\Entity\Course\Course;

use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

interface RecordEventInterface
{
    public function getType(): TypeOfRecord;

    public function getStudent(): Student;

    public function getCourse(): ?Course;

    public function getDescription(): string;

    public function getDate(): CarbonImmutable;
}
