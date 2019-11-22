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

namespace Britannia\Domain\Repository;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Lesson;
use Carbon\CarbonImmutable;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface LessonRepositoryInterface
{

    /**
     * @param Course $course
     * @param CarbonImmutable $day
     * @param int $limit
     * @return Lesson[]
     */
    public function getLastByCourse(Course $course, CarbonImmutable $day, int $limit = 5): array;

    /**
     * @param CarbonImmutable $day
     * @return Lesson[]
     */
    public function findByDay(CarbonImmutable $day): array;

}
