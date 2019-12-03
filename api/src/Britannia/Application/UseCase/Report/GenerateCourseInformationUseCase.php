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

namespace Britannia\Application\UseCase\Report;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Discount\Discount;
use Carbon\CarbonImmutable;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class GenerateCourseInformationUseCase implements UseCaseInterface
{
    public function handle(GenerateCourseInformation $command)
    {
        $course = $command->getCourse();
        $student = $command->getStudent();
        $discount = $this->getDiscountFromStudent($student);

        return [
            'course' => $course,
            'student' => $student,
            'discount' => $discount
        ];
    }

    /**
     * @param Student|null $student
     * @return Discount
     */
    private function getDiscountFromStudent(?Student $student): Discount
    {
        $discount = Discount::byDefault();

        if ($student instanceof Student) {
            $discount = $student->getDiscount();
        }

        return $discount;
    }


}
