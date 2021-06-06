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

namespace Britannia\Domain\VO\Student\Tutor;


use Britannia\Domain\Entity\Student\Tutor;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

final class ChoicedTutor
{
    use Validable;

    /**
     * @var Tutor
     */
    private Tutor $tutor;
    /**
     * @var string
     */
    private string $description;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\ChoicedTutor([
            'required' => $options['required']
        ]);
    }

    public static function make(Tutor $tutor, string $description): self
    {
        return new self($tutor, $description);
    }

    public function __construct(Tutor $tutor, string $description)
    {
        $this->tutor = $tutor;
        $this->description = $description;
    }

    /**
     * @return Tutor
     */
    public function tutor(): Tutor
    {
        return $this->tutor;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }
}
