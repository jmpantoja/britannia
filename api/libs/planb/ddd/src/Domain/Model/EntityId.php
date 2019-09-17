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

namespace PlanB\DDD\Domain\Model;


use Ramsey\Uuid\Uuid;

class EntityId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     * @throws \Exception
     */
    public function __construct($id = null)
    {

        if (is_null($id)) {
            $this->id = Uuid::uuid4()->toString();
            return;
        }

        $this->id = (string)$id;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param UserId $userId
     *
     * @return bool
     */
    public function equals(UserId $userId)
    {
        return $this->id() === $userId->id();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }
}
