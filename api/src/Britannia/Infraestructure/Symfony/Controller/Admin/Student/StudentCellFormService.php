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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Student;


use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Britannia\Infraestructure\Symfony\Form\Type\Course\CourseStudentType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Twig\Environment;

final class StudentCellFormService
{
    private StudentRepositoryInterface $studentRepository;
    private CourseRepositoryInterface $courseRepository;
    private FormFactoryInterface $formFactory;
    private Environment $twig;

    public function __construct(StudentRepositoryInterface $studentRepository,
                                CourseRepositoryInterface  $courseRepository,
                                FormFactoryInterface       $formFactory,
                                Environment                $twig
    )
    {
        $this->studentRepository = $studentRepository;
        $this->courseRepository = $courseRepository;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function buildResponse(string $studentId, string $courseId, string $uniqId, array $formTheme)
    {

        return [
            'form' => $this->createFormView($studentId, $courseId, $uniqId, $formTheme)
        ];
    }


    private function createFormView(string $studentId, string $courseId, string $uniqId, array $formTheme)
    {
        $student = $this->studentRepository->find($studentId);
        $course = $this->courseRepository->find($courseId);

        $studentCourse = StudentCourse::make($student, $course);

        $builder = $this->formFactory->createNamedBuilder($uniqId, FormType::class);
        $builder->add('courseHasStudents', FormType::class);

        $key = (string)$studentCourse->student()->id();
        $builder->get('courseHasStudents')
            ->add($key, CourseStudentType::class, [
                'label' => false,
                'required' => false,
                'studentCourse' => $studentCourse
            ]);

//
//        $builder = $this->formFactory->createNamedBuilder('cell', FormType::class);
//        $key = (string)$studentCourse->id();
//
//        $builder->add($key, CourseStudentType::class, [
//            'label' => false,
//            'required' => false,
//            'studentCourse' => $studentCourse
//        ]);

        $form = $builder->getForm();
        $formView = $form->createView();
        $this->setFormTheme($formView, $formTheme);

        return $formView;
    }


    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, array $formTheme): void
    {
        $this->twig->getRuntime(FormRenderer::class)->setTheme($formView, $formTheme);
    }


}
