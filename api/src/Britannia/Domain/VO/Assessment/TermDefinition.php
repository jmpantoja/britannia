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

namespace Britannia\Domain\VO\Assessment;


use PlanB\DDD\Domain\VO\Percent;

final class TermDefinition
{

    /**
     * @var TermName
     */
    private TermName $termName;
    /**
     * @var Percent
     */
    private Percent $unitsWeight;
    /**
     * @var int
     */
    private int $numOfUnits;

    public static function make(TermName $termName, Percent $unitsWeight, int $numOfUnits): self
    {
        return new self($termName, $unitsWeight, $numOfUnits);
    }

    public function __construct(TermName $termName, Percent $unitsWeight, int $numOfUnits)
    {

        $this->termName = $termName;
        $this->unitsWeight = $unitsWeight;
        $this->numOfUnits = $numOfUnits;
    }

    /**
     * @return TermName
     */
    public function termName(): TermName
    {
        return $this->termName;
    }

    /**
     * @return Percent
     */
    public function unitsWeight(): Percent
    {
        return $this->unitsWeight;
    }

    /**
     * @return int
     */
    public function numOfUnits(): int
    {
        return $this->numOfUnits;
    }
}
