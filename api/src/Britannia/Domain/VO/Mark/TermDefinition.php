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
use Serializable;

class TermDefinition implements Serializable
{
    use Validable;

    private NumOfUnits $numOfUnits;

    private Term $term;

    private Percent $weighOfUnits;

    private function __construct(Term $termName, NumOfUnits $numOfUnits, Percent $unitsWeight)
    {
        $this->term = $termName;
        $this->numOfUnits = $numOfUnits;
        $this->weighOfUnits = $unitsWeight;
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Term([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(Term $termName, NumOfUnits $numOfUnits, Percent $unitsWeight): self
    {
        return new self($termName, $numOfUnits, $unitsWeight);
    }

    /**
     * @return NumOfUnits
     */
    public function numOfUnits(): NumOfUnits
    {
        return $this->numOfUnits;
    }

    /**
     * @return Term
     */
    public function term(): Term
    {
        return $this->term;
    }

    /**
     * @return Percent
     */
    public function weighOfUnits(): Percent
    {
        return $this->weighOfUnits;
    }

    /**
     * @return Percent
     */
    public function weighOfExam(): Percent
    {
        return $this->weighOfUnits->complementary();
    }


    public function serialize()
    {
        return serialize([
            'termName' => $this->term->getName(),
            'numOfUnits' => $this->numOfUnits->getName(),
            'weighOfUnits' => $this->weighOfUnits->toInt()
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized, [
            'allowed_classes' => false
        ]);

        $this->term = Term::byName($data['termName']);
        $this->numOfUnits = NumOfUnits::byName($data['numOfUnits']);
        $this->weighOfUnits = Percent::make($data['weighOfUnits']);

    }
}
