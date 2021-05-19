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

namespace Britannia\Infraestructure\Symfony\Form\Type\Student;


use Britannia\Domain\Repository\SchoolCourseRepositoryInterface;
use Britannia\Domain\VO\SchoolCourse\SchoolCourse;
use Britannia\Domain\VO\SchoolCourse\SchoolHistory;
use Britannia\Domain\VO\SchoolCourse\SchoolItinerary;
use Britannia\Domain\VO\SchoolCourse\SchoolYear;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SchoolCourseType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $year = SchoolYear::make();
        $builder->add('current', ChoiceType::class, [
            'label' => $year->name(),
            'choices' => $this->choices($options['birthDay'], $options['data']),
            'choice_value' => $this->choiceValue()
        ]);

    }

    private function choices(?CarbonImmutable $birthDay, SchoolHistory $history): array
    {

        if (is_null($birthDay)) {
            return [];
        }

        $age = $this->calculeAgeByBirthday($birthDay);
        $numOfFailedCourses = $history->numOfFailedCourses();

        return SchoolItinerary::byAge($age, $numOfFailedCourses)
            ->map(fn(SchoolCourse $course) => $course->name())
            ->flip()
            ->reverse()
            ->toArray();
    }

    private function calculeAgeByBirthday(CarbonImmutable $birthDay): int
    {
        $year = SchoolYear::make()->to();
        return $year - $birthDay->year - 1;
    }

    private function choiceValue()
    {
        return function ($course) {
            if ($course instanceof SchoolCourse) {
                return $course->key();
            }
            return $course;
        };
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SchoolHistory::class,
            'birthDay' => null
        ]);

        $resolver->setAllowedTypes('birthDay', [CarbonImmutable::class, 'null']);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data, SchoolHistory $history = null)
    {
        return $history->update($data['current']);
    }

}
