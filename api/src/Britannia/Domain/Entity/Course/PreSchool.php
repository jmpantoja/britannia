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

namespace Britannia\Domain\Entity\Course;


final class PreSchool extends Course
{
    /**
     * @var null|string
     */
    private $schoolCourse;

    public function update(CourseDto $dto): PreSchool
    {
        $this->schoolCourse = $dto->schoolCourse;
        return parent::update($dto);
    }

    /**
     * @return string|null
     */
    public function schoolCourse(): ?string
    {
        return $this->schoolCourse;
    }
}
