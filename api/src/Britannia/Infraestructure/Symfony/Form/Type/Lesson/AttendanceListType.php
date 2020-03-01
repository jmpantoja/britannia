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

namespace Britannia\Infraestructure\Symfony\Form\Type\Lesson;


use Britannia\Domain\Entity\Attendance\AttendanceList;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceListType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $lesson = $options['lesson'];
        if (!($lesson instanceof Lesson)) {
            return;
        }


        foreach ($lesson->students() as $student) {

            $key = (string)$student->id();
            $builder->add($key, AttendanceType::class, [
                'label' => false,
                'required' => false,
                'student' => $student,
                'lesson' => $lesson
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'lesson'
        ]);
        $resolver->setAllowedTypes('lesson', Lesson::class);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        $data = array_filter($data);
        return AttendanceList::collect($data);
    }
}
