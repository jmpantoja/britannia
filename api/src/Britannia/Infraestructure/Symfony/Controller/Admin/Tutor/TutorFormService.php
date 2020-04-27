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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Tutor;


use Britannia\Domain\Repository\TutorRepositoryInterface;
use Britannia\Infraestructure\Symfony\Form\Type\Tutor\TutorType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Twig\Environment;

final class TutorFormService
{
    /**
     * @var TutorRepositoryInterface
     */
    private TutorRepositoryInterface $tutorRepository;
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;
    /**
     * @var Environment
     */
    private Environment $twig;


    /**
     * TutorFormService constructor.
     */
    public function __construct(TutorRepositoryInterface $tutorRepository,
                                FormFactoryInterface $formFactory,
                                Environment $twig)
    {
        $this->tutorRepository = $tutorRepository;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function buildResponse(string $tutorId, string $uniqId, string $name, array $formTheme)
    {
        return [
            'form' => $this->createFormView($tutorId, $uniqId, $name, $formTheme)
        ];
    }

    private function createFormView(string $tutorId, string $uniqId, string $name, array $formTheme)
    {
        $tutor = $this->tutorRepository->find($tutorId);

        $builder = $this->formFactory->createNamedBuilder($uniqId, FormType::class);
        $builder->add($name, FormType::class);

        $builder->get($name)
            ->add('tutor', TutorType::class, [
                'label' => false,
                'mapped' => false,
                'field' => $name,
                'data' => $tutor
            ]);

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
