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

namespace PlanB\DDD\Domain\Enum;


abstract class Enum extends \MyCLabs\Enum\Enum
{
    public static function create(string $name): ?self
    {
        if (static::hasName($name)) {
            return static::byName($name);
        }
        if (static::isValid($name)) {
            $name = static::search($name);
            return static::byName($name);
        }

        return null;
    }

    public static function byOrdinal(int $num)
    {
        $keys = array_keys(static::getConstants());

        if (!isset($keys[$num])) {
            $msg = sprintf('No existe el ordinal %s para %s', $num, static::class);
            throw new \Exception($msg);
        }

        $name = $keys[$num];
        return static::byName($name);
    }


    public static function byName(string $value): Enum
    {
        return static::__callStatic($value, []);
    }

    public static function getConstants(): array
    {
        return static::toArray();
    }

    public function getName()
    {
        return $this->getKey();
    }

    public static function hasName(string $value)
    {
        return static::isValidKey($value);
    }

    /**
     * Compare this enumerator against another and check if it's the same.
     *
     * @param Enum|null|bool|int|float|string|array $enumerator An enumerator object or value
     * @return bool
     */
    final public function is($enumerator)
    {
        return $this->equals($enumerator);
    }

    public function __toString()
    {
        return $this->getKey();
    }
}
