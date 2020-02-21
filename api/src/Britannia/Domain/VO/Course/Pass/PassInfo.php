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

namespace Britannia\Domain\VO\Course\Pass;


use Britannia\Domain\Entity\Course\Pass\PassList;
use Britannia\Domain\VO\Course\Locked\Locked;

final class PassInfo
{
    /**
     * @var PassList
     */
    private PassList $passList;
    /**
     * @var Locked
     */
    private Locked $locked;

    public static function make(PassList $passList, Locked $locked): self
    {
        return new self($passList, $locked);
    }

    private function __construct(PassList $passList, Locked $locked)
    {
        $this->passList = $passList;
        $this->locked = $locked;
    }

    /**
     * @return PassList
     */
    public function passList(): PassList
    {
        return $this->passList;
    }

    /**
     * @return Locked
     */
    public function locked(): Locked
    {
        return $this->locked;
    }
}
