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

namespace Britannia\Domain\VO\Student\Alert;


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

final class Alert
{
    use Validable;

    /**
     * @var bool
     */
    private bool $alert = false;

    /**
     * @var string|null
     */
    private ?string $description = null;


    public static function default(): self
    {
        return new self();
    }

    public static function make(string $description)
    {
        return new self($description);
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Alert([
            'required' => $options['required']
        ]);
    }

    private function __construct(?string $description = null)
    {
        $this->setDescription($description);
    }

    /**
     * @param string|null $description
     * @return Alert
     */
    private function setDescription(?string $description): Alert
    {
        if (is_null($description)) {
            $this->alert = false;
            return $this;
        }

        $this->alert = true;
        $this->description = $this->sanitize($description);
        return $this;
    }

    private function sanitize(string $description): string
    {
        $description = preg_replace("/[\r\n|\n|\r]+/", '', $description);
        $description = str_replace('<p>&nbsp;</p>', '', $description);
        return $description;
    }


    /**
     * @return bool
     */
    public function isAlert(): bool
    {
        return $this->alert;
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description;
    }


}
