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


use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;

final class Adult extends Course
{
    /**
     * @var null|Intensive
     */
    private $intensive;

    /**
     * @var null|Examiner
     */
    private $examiner;

    /**
     * @var null|Level
     */
    private $level;

    public function update(CourseDto $dto): self
    {
        $this->examiner = $dto->examiner;
        $this->level = $dto->level;
        $this->intensive = $dto->intensive;
        return parent::update($dto);
    }

    /**
     * @return Intensive|null
     */
    public function intensive(): ?Intensive
    {
        return $this->intensive;
    }



    /**
     * @return Examiner|null
     */
    public function examiner(): ?Examiner
    {
        return $this->examiner;
    }

    /**
     * @return Level|null
     */
    public function level(): ?Level
    {
        return $this->level;
    }


}
