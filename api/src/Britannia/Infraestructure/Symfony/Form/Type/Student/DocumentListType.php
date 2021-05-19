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


use Britannia\Domain\Entity\Student\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class DocumentListType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
        ]);

        $resolver->setRequired('student');
        $resolver->setAllowedTypes('student', [Student::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $student = $options['student'];
        $view->vars['reports'] = $this->getReports($student);

        return parent::finishView($view, $form, $options);
    }

    /**
     * @param $student
     * @return array
     */
    private function getReports($student): array
    {
        return [
            [
                'anchor' => 'Ficha Personal',
                'href' => $this->generateUrl('student_registration_form_report', $student)
            ]
        ];
    }

    private function generateUrl(string $route, Student $student)
    {
        return $this->urlGenerator->generate($route, ['id' => $student->id()]);
    }


}
