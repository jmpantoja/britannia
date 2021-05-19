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


use Britannia\Domain\Entity\Image\Image;
use Britannia\Domain\VO\Attachment\FileInfo;

class Photo extends Image
{
    /**
     * @var StaffMember
     */
    private $staffMember;


    public static function make(StaffMember $staffMember, FileInfo $info): self
    {
        return new self($staffMember, $info);
    }

    private function __construct(StaffMember $staffMember, FileInfo $info)
    {
        $this->staffMember = $staffMember;
        parent::__construct($info);

    }

    /**
     * @return StaffMember
     */
    public function staffMember(): StaffMember
    {
        return $this->staffMember;
    }

}
