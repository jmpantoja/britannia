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

use Britannia\Domain\VO\Mark\Validator;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class TermDefinition
{
    use Validable;

    private TermName $termName;

    private Percent $unitsWeight;

    private NumOfUnits $numOfUnits;

    /**
     * @var NumOfUnits
     */
    private NumOfUnits $completedUnits;


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\TermDefinition([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(TermName $termName, Percent $unitsWeight, NumOfUnits $numOfUnits, NumOfUnits $completedUnits): self
    {
        $input = self::assert([
            'termName' => $termName,
            'unitsWeight' => $unitsWeight,
            'numOfUnits' => $numOfUnits,
            'completedUnits' => $completedUnits
        ]);

        $input = array_values($input);

        return new self(...$input);
    }

    private function __construct(TermName $termName, Percent $unitsWeight, NumOfUnits $numOfUnits, NumOfUnits $completedUnits)
    {
        $this->termName = $termName;
        $this->unitsWeight = $unitsWeight;
        $this->numOfUnits = $numOfUnits;
        $this->completedUnits = $completedUnits;
    }

    public function hasUnits(): bool
    {
        return $this->numOfUnits->getValue() !== 0;
    }

    /**
     * @return NumOfUnits
     */
    public function numOfUnits(): NumOfUnits
    {
        return $this->numOfUnits;
    }

    /**
     * @return NumOfUnits
     */
    public function completedUnits(): NumOfUnits
    {
        return $this->completedUnits;
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
}
