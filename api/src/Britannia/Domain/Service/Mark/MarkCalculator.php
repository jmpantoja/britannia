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

namespace Britannia\Domain\Service\Mark;


use Britannia\Domain\Entity\Unit\UnitStudent;
use Britannia\Domain\Entity\Unit\UnitStudentList;
use Britannia\Domain\VO\Mark\TermDefinitionList;
use Britannia\Domain\VO\Mark\UnitsDefinition;

final class MarkCalculator
{

    public static function make(): self
    {
        return new self();
    }

    private function __construct()
    {

    }

    public function updateTotal(UnitStudentList $unitStudentList,
                                UnitsDefinition $definition): UnitStudentList
    {
        $data = $this->collectData($unitStudentList);

        $terms = $definition->terms();
        $this->updateAll($terms, $data);

        return $unitStudentList;
    }


    private function calculePercent(UnitStudentList $input, TermDefinitionList $terms)
    {
        $element = $this->firstElement($input);
        $term = $element->unit()->term();

        return $terms->percentByTerm($term);
    }

    /**
     * @param UnitStudentList $input
     * @return mixed
     */
    private function firstElement(UnitStudentList $input): UnitStudent
    {
        return $input->values()->first();
    }

    /**
     * @param UnitStudentList $unitStudentList
     * @return array
     */
    private function collectData(UnitStudentList $unitStudentList): array
    {
        $input = [];
        /** @var UnitStudent $unitStudent */
        foreach ($unitStudentList as $unitStudent) {
            $key = $unitStudent->hash();
            $input[$key][] = $unitStudent;
        }
        return $input;
    }

    /**
     * @param TermDefinitionList $terms
     * @param array $data
     */
    private function updateAll(TermDefinitionList $terms, array $data): void
    {
        foreach ($data as $studentTerm) {
            $unitStudentList = UnitStudentList::collect($studentTerm);
            $percent = $this->calculePercent($unitStudentList, $terms);

            MarkUpdater::update($unitStudentList, $percent);
        }
    }
}
