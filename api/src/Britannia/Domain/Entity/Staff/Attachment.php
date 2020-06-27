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

namespace Britannia\Domain\Entity\Staff;


use Britannia\Domain\Entity\Attachment\Attachment as BaseAttachment;
use Britannia\Domain\VO\Attachment\FileInfo;

class Attachment extends BaseAttachment
{
    /**
     * @var StaffMember
     */
    private $staff;


    public static function make(StaffMember $staffMember, FileInfo $info, ?string $description = null): self
    {
        return new self($staffMember, $info, $description);
    }

    private function __construct(StaffMember $staffMember, FileInfo $info, ?string $description)
    {
        $this->staff = $staffMember;
        parent::__construct($info, $description);
    }

    /**
     * @return mixed
     */
    public function staff()
    {
        return $this->staff;
    }
}
