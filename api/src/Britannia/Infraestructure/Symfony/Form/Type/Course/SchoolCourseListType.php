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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course;

use Britannia\Domain\VO\SchoolCourse\SchoolCourse;
use Britannia\Domain\VO\SchoolCourse\SchoolItinerary;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolCourseListType extends AbstractSingleType
{
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->choices(),
            'multiple' => true
        ]);
    }

    private function choices(): array
    {
        return SchoolItinerary::courses()
            ->map(fn(SchoolCourse $course) => $course->name())
            ->flip()
            ->toArray();
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data, $value = null)
    {
        return $data;
    }
}
