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

namespace Britannia\Domain\Entity\Mark;


use Britannia\Domain\VO\Mark\TermName;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;

class Unit
{
    /**
     * @var UnitId
     */
    private $id;

    /**
     * @var Term
     */
    private $term;

    /**
     * @var TermName
     */
    private $termName;

    /**
     * @var PositiveInteger
     */
    private $number;

    /**
     * @var CarbonImmutable
     */
    private $completedAt;

    private function __construct(Term $term, PositiveInteger $number)
    {
        $this->id = new UnitId();

        $this->term = $term;
        $this->termName = $term->getName();
        $this->number = $number;
    }

    public static function make(Term $term, PositiveInteger $number)
    {
        return new self($term, $number);
    }

    /**
     * @return UnitId
     */
    public function getId(): UnitId
    {
        return $this->id;
    }

    public function getLabel(): string
    {

        return sprintf('Unidad #%s', ...[
            $this->getPosition()
        ]);
    }

    /**
     * @return int
     */
    private function getPosition(): int
    {
        $termOrder = $this->getTermName()->getOrder();
        $totalOfUnits = $this->getTerm()->totalOfUnits();

        $number = $this->getNumber()->toInt();

        return ($termOrder - 1) * $totalOfUnits + $number;
    }

    /**
     * @return TermName
     */
    public function getTermName(): TermName
    {
        return $this->termName;
    }

    /**
     * @return Term
     */
    public function getTerm(): Term
    {
        return $this->term;
    }

    /**
     * @return PositiveInteger
     */
    public function getNumber(): PositiveInteger
    {
        return $this->number;
    }

    public function getName(): string
    {

        return sprintf('%s. Unidad %s', ...[
            $this->getTermName()->getValue(),
            $this->getNumber()->toInt()
        ]);
    }

    /**
     * @return CarbonImmutable
     */
    public function getCompletedAt(): ?CarbonImmutable
    {
        return $this->completedAt;
    }

    public function isCompleted(): bool
    {
        return $this->completedAt instanceof CarbonImmutable;
    }

    public function compare(Unit $other): int
    {
        return $this->getPosition() <=> $other->getPosition();
    }
}
