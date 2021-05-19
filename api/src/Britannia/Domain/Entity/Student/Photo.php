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


use Britannia\Domain\Entity\Image\Image;
use Britannia\Domain\VO\Attachment\FileInfo;

class Photo extends Image
{
    /**
     * @var Student
     */
    private $student;


    public static function make(Student $student, FileInfo $info): self
    {
        return new self($student, $info);
    }

    private function __construct(Student $student, FileInfo $info)
    {
        $this->student = $student;
        parent::__construct($info);

    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

}
