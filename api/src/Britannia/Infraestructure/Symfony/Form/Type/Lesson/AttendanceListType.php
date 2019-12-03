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


use Britannia\Domain\Entity\Course\Attendance;
use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Britannia\Infraestructure\Doctrine\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        $course = $lesson->getCourse();

        foreach ($course->getStudents() as $student) {

            $key = (string)$student->getId();
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

        $resolver->setDefaults([
            'data_class' => Collection::class
        ]);

        $resolver->setAllowedTypes('lesson', Lesson::class);

    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        return [1, 2, 3];

        $attendances = new ArrayCollection();

        $data = array_filter($data);

        foreach ($data as $attendance) {
            $attendances->add($attendance);
        }

        return $attendances;
    }
}
