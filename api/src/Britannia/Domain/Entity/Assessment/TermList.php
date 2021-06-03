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

namespace Britannia\Domain\Entity\Assessment;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Assessment\MarkReportAverageCalculator;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\EntityList;

final class TermList extends EntityList
{


    protected function typeName(): string
    {
        return Term::class;
    }

    public function marksByStudent(Student $student): MarkReport
    {
        $input = $this->values()
            ->filter(fn(Term $term) => $term->hasStudent($student))
            ->map(fn(Term $term) => $term->total())
            ->toArray();

        return MarkReportAverageCalculator::collect(...$input)
            ->calcule();
    }
    
    public function setLimits(TermName $termName, CarbonImmutable $start, ?CarbonImmutable $end = null): self
    {
        $this->values()
            ->filter(fn(Term $term) => $term->hasTermName($termName))
            ->each(fn(Term $term) => $term->setLimits($start, $end));

        $nextTerm = $termName->next();
        if ($nextTerm instanceof TermName && $end instanceof CarbonImmutable) {
            $this->setLimits($nextTerm, $end->addDay());
        }

        return $this;
    }


    public function sortByStudentName(): self
    {
        $input = $this->values()
            ->sortBy(fn(Term $term) => (string)$term->student());

        return self::collect($input);
    }


    public function updateSkills(SetOfSkills $skills): self
    {
        $this->values()
            ->each(fn(Term $term) => $term->updateSkills(($skills)));
        return $this;
    }

    public function updateDefintion(TermDefinition $defintion): self
    {
        $this->values()
            ->each(fn(Term $term) => $term->updateDefintion($defintion));

        return $this;
    }

}
