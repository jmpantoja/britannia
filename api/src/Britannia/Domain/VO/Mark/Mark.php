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

namespace Britannia\Domain\VO\Mark;


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class Mark
{
    use Validable;

    /**
     * @var float
     */
    private $mark;

    private function __construct(float $mark)
    {
        $this->mark = round($mark, 2);
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Mark([
            'required' => $options['required'] ?? false
        ]);
    }

    public static function make(float $mark): self
    {
        $mark = self::assert($mark);
        return new self($mark);
    }

    /**
     * @return float
     */
    public function mark(): float
    {
        return $this->mark;
    }

    public function __toString()
    {
        return (string)$this->mark();
    }


}
