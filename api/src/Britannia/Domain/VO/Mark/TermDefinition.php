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


use Britannia\Domain\VO\Course\Term\Validator;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class TermDefinition implements \Serializable
{
    use Validable;

    /**
     * @var NumOfUnits
     */
    private $numOfUnits;
    /**
     * @var TermName
     */
    private $termName;
    /**
     * @var Percent
     */

    private $weighOfUnits;

    private function __construct(TermName $termName, NumOfUnits $numOfUnits, Percent $unitsWeight)
    {

        $this->termName = $termName;
        $this->numOfUnits = $numOfUnits;
        $this->weighOfUnits = $unitsWeight;
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new \Britannia\Domain\VO\Mark\Validator\Term([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(TermName $termName, NumOfUnits $numOfUnits, Percent $unitsWeight): self
    {
        return new self($termName, $numOfUnits, $unitsWeight);
    }

    /**
     * @return NumOfUnits
     */
    public function getNumOfUnits(): NumOfUnits
    {
        return $this->numOfUnits;
    }

    /**
     * @return TermName
     */
    public function getTermName(): TermName
    {
        return $this->termName;
    }

    public function getName(): string
    {
        return $this->termName->getName();
    }

    /**
     * @return Percent
     */
    public function getWeighOfUnits(): Percent
    {
        return $this->weighOfUnits;
    }

    public function serialize()
    {
        return serialize([
            'termName' => $this->termName->getName(),
            'numOfUnits' => $this->numOfUnits->getName(),
            'weighOfUnits' => $this->weighOfUnits->toInt()
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized, [
            'allowed_classes' => false
        ]);

        $this->termName = TermName::byName($data['termName']);
        $this->numOfUnits = NumOfUnits::byName($data['numOfUnits']);
        $this->weighOfUnits = Percent::make($data['weighOfUnits']);

    }
}
