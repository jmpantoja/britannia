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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CourseHasStudentsType extends AbstractCompoundType
{
    private Pool $adminPool;
    private StudentRepositoryInterface $studentRepository;
    private AdapterInterface $cache;


    public function __construct(Pool $adminPool, StudentRepositoryInterface $studentRepository, TagAwareCacheInterface $cache)
    {
        $this->adminPool = $adminPool;
        $this->studentRepository = $studentRepository;
        $this->cache = $cache;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['course'];
        $students = $this->cleanDuplicates($course->courseHasStudents());

        $builder->add('hola', HiddenType::class, [
            'mapped' => false
        ]);

        foreach ($students as $student) {
            $key = (string)$student->student()->id();
            $builder->add($key, CourseStudentType::class, [
                'label' => false,
                'required' => false,
                'studentCourse' => $student,
            ]);
        }
    }

    private function cleanDuplicates(array $studentCourses): array
    {
        return collect($studentCourses)
            ->sort(function (StudentCourse $first, StudentCourse $second) {
                return $first->compareByJoinDate($second);
            })
            ->mapWithKeys(function (StudentCourse $item) {
                $key = (string)$item->student()->id();
                return [$key => $item];
            })
            ->sort(function (StudentCourse $first, StudentCourse $second) {
                $comparison = $second->isActive() <=> $first->isActive();
                if ($comparison === 0) {
                    $comparison = (string)$first->student()->fullName() <=> (string)$second->student()->fullName();
                }
                return $comparison;
            })
            ->toArray();
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('allow_extra_fields', true);
        $resolver->setRequired([
            'course'
        ]);

        $resolver->setAllowedTypes('course', Course::class);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        /** @var Course $course */
        $course = $options['course'];
        $uniqId = $view->vars['sonata_admin']['admin']->getUniqId();

        $students = $course->courseHasStudents();
        $selected = collect($students)
            ->map(function (StudentCourse $studentCourse) {
                return (string)$studentCourse->student()->id();
            })
            ->toArray();

        $view->vars['students'] = $this->getStudentsList($course, $selected);
        $view->vars['course'] = $course->id();
        $view->vars['endpoint'] = $this->endpoint($uniqId);
        $view->vars['selected'] = $selected;
        $view->vars['finalized'] = $course->isFinalized();
    }

    private function endpoint(string $uniqId)
    {
        return $this->adminPool
            ->getAdminByAdminCode('admin.student')
            ->generateUrl('student_cell', [
                'uniqId' => $uniqId
            ]);
    }

    private function getStudentsList(Course $course, array $selected)
    {
        $className = normalize_key(get_class($this) . '-' . get_class($course));
        $studentList = $this->cache->get($className, function (ItemInterface $item) use ($course) {
            $item->expiresAfter(60 * 60 * 24);
            $item->tag(normalize_key(Student::class));

            $choices = $this->studentRepository->findStudentsOfTheCorrectAge($course);
            return collect($choices)
                ->mapWithKeys(function ($item) {
                    return [(string)$item => (string)$item->id()];
                })
                ->toArray();
        });

        return collect($studentList)
            ->filter(function (string $studentId) use ($selected) {
                return !in_array($studentId, $selected);
            })
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function mapFormsToData($forms, &$data): void
    {
        $forms = iterator_to_array($forms);
        $parentForm = $this->getParentForm($forms);

        $extraData = collect($parentForm->getExtraData())
            ->map(function ($data, $id) {
                if ($data['missed']) {
                    return null;
                }

                $data['singlePaid'] = $data['singlePaid'] ?? 'no';
                $singlePaid = $data['singlePaid'] === 'yes';

                $student = $this->studentRepository->find($id);
                $course = $this->getOption('course');
                $studentCourse = StudentCourse::make($student, $course);

                $studentCourse->setSinglePaid($singlePaid);

                return $studentCourse;
            })
            ->filter()
            ->toArray();

        $values = array_map(function ($form) {
            return $form->getData();
        }, $forms);

        $all = array_merge($values, $extraData);

        $data = $this->customMapping($all);
    }

    public function customMapping(array $data)
    {
        $data = array_filter($data);
        return StudentCourseList::collect(array_values($data));
    }
}
