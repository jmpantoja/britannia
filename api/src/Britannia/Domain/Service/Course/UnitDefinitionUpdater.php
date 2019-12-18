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

namespace Britannia\Domain\Service\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Mark\TermList;
use Britannia\Domain\VO\Mark\UnitsDefinition;

class UnitDefinitionUpdater
{
//    public function updateUnits(Course $course, UnitsDefinition $unitsDefinition)
//    {
//        if ($unitsDefinition->isLocked()) {
//            return;
//        }
//
//        if ($unitsDefinition->shouldBeResetted()) {
//            $course->getTerms()->clear();
//            $termList = TermList::make($course->getTerms());
//        }
//
//        if ($unitsDefinition->shouldBeUpdated()) {
//            $termList = TermList::make($course->getTerms());
//        }
//
//        $terms = $unitsDefinition->getTerms();
//        $skills = $unitsDefinition->getSkills();
//
//        foreach ($terms as $definition) {
//            $termList->add($course, $skills, $definition);
//        }
//
//        $course->setTerms($termList->toCollection());
//    }

}
