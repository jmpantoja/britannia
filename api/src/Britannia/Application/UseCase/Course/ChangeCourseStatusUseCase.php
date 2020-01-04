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

namespace Britannia\Application\UseCase\Course;


use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class ChangeCourseStatusUseCase implements UseCaseInterface
{
    public function handle(ChangeCourseStatus $command)
    {
        $students = $command->students();
        foreach ($students as $student) {
            $student->updateStatus();
        }
    }
}
