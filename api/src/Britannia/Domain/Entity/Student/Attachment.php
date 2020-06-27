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


use Britannia\Domain\Entity\Attachment\Attachment as BaseAttachment;
use Britannia\Domain\VO\Attachment\FileInfo;

class Attachment extends BaseAttachment
{
    /**
     * @var Student
     */
    private $student;


    public static function make(Student $student, FileInfo $info, ?string $description = null): self
    {
        return new self($student, $info, $description);
    }

    private function __construct(Student $staffMember, FileInfo $info, ?string $description)
    {
        $this->student = $staffMember;
        parent::__construct($info, $description);
    }

    /**
     * @return mixed
     */
    public function student()
    {
        return $this->student;
    }
}
