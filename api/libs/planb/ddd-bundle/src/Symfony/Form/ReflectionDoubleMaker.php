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

namespace PlanB\DDDBundle\Symfony\Form;


class ReflectionDoubleMaker
{
    public function create(string $className, array $attributes)
    {

        $reflection = new \ReflectionClass($className);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($attributes as $name => $value) {
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }

        return $object;
    }
}
