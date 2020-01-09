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

namespace Britannia\Infraestructure\Symfony\Service\Assessment;


use Britannia\Domain\Repository\TermsParametersInterface;
use Britannia\Domain\VO\Assessment\TermName;
use Carbon\CarbonImmutable;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TermParameters implements TermsParametersInterface
{

    /**
     * @var CarbonImmutable[]
     */
    private $dates = array();

    public function __construct(ParameterBagInterface $parameters)
    {

        $dates = $parameters->get('dates');

        $this->dates['course_starts_on'] = $this->toDate($dates['course_starts_on']);
        $this->dates['second_term_starts_on'] = $this->toDate($dates['second_term_starts_on']);
        $this->dates['third_term_starts_on'] = $this->toDate($dates['third_term_starts_on']);
        $this->dates['course_ends_on'] = $this->toDate($dates['course_ends_on']);
    }

    private function toDate(array $pieces): CarbonImmutable
    {
        $day = $pieces['day'];
        $month = $pieces['month'];

        return CarbonImmutable::today()
            ->setDay($day)
            ->setMonth($month);
    }


    public function startDateByTerm(TermName $termName): CarbonImmutable
    {
        if ($termName->isFirst()) {
            return $this->dates['course_starts_on'];
        } elseif ($termName->isSecond()) {
            return $this->dates['second_term_starts_on'];
        } else {
            return $this->dates['third_term_starts_on'];
        }
    }

    public function endDateByTerm(TermName $termName): CarbonImmutable
    {
        if ($termName->isFirst()) {
            return $this->dates['second_term_starts_on']->subDay();
        } elseif ($termName->isSecond()) {
            return $this->dates['third_term_starts_on']->subDay();
        } else {
            return $this->dates['course_ends_on'];
        }
    }
}
