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

namespace PlanB\DDD\Domain\VO;


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class RGBA
{
    use Validable;


    /**
     * @var int
     */
    private $red;
    /**
     * @var int
     */
    private $green;
    /**
     * @var int
     */
    private $blue;
    /**
     * @var float
     */
    private $alpha;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new \PlanB\DDD\Domain\VO\Validator\RGBA();
    }

    public static function make(int $red, int $green, int $blue, float $alpha = 1.0): self
    {
        $data = self::assert([
            'red' => $red,
            'green' => $green,
            'blue' => $blue,
            'alpha' => $alpha,

        ]);

        return new self(...[
            $data['red'],
            $data['green'],
            $data['blue'],
            $data['alpha'],
        ]);
    }

    private function __construct(int $red, int $green, int $blue, float $alpha = 1.0)
    {

        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    /**
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * @return int
     */
    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * @return float
     */
    public function getAlpha(): float
    {
        return $this->alpha;
    }


    public function toHtml(): string
    {
        if ($this->alpha === 1.0) {
            return sprintf('#%s%s%s', ...[
                $this->toHex($this->red),
                $this->toHex($this->green),
                $this->toHex($this->blue),
            ]);
        }


        return sprintf('#%s%s%s%s', ...[
            $this->toHex($this->red),
            $this->toHex($this->green),
            $this->toHex($this->blue),
            $this->toHex((int)$this->alpha * 256),
        ]);
    }

    private function toHex(int $number): string
    {
        $hex = dechex($number);
        return str_pad($hex, 2, '0', STR_PAD_LEFT);
    }

}
