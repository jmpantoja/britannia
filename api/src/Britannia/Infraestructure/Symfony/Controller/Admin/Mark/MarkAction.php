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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Mark;


use PlanB\DDDBundle\Sonata\ModelManager;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Twig\Environment;

abstract class MarkAction
{

    /**
     * @var FormFactory
     */
    private FormFactory $formFactory;

    /**
     * @var string
     */
    private string $uniqId;
    /**
     * @var array
     */
    private array $formTheme;
    /**
     * @var RequestParameters
     */
    private RequestParameters $parameters;
    /**
     * @var ModelManager
     */
    private ModelManager $modelManager;
    /**
     * @var Environment
     */
    private Environment $twig;

    public function __construct(ModelManager $modelManager,
                                RequestParameters $parameters,
                                FormFactoryInterface $formFactory,
                                Environment $twig
    )
    {
        $this->modelManager = $modelManager;
        $this->parameters = $parameters;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function initialize(string $uniqId, array $formTheme): self
    {
        $this->uniqId = $uniqId;
        $this->formTheme = $formTheme;

        return $this;
    }

    /**
     * @return ModelManager
     */
    public function modelManager(): ModelManager
    {
        return $this->modelManager;
    }

    public function handle(): array
    {
        $params = $this->execute($this->parameters);
        $params['form'] = $this->createFormView();

        return $params;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $params
     * @return FormView
     */
    private function createFormView(): FormView
    {
        $builder = $this->formFactory->createNamedBuilder($this->uniqId, FormType::class);

        $this->configureForm($builder, $this->parameters);

        $form = $builder->getForm();
        $formView = $form->createView();
        $this->setFormTheme($formView);

        return $formView;
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView): void
    {
//        $twig = $this->get('twig');
        $this->twig->getRuntime(FormRenderer::class)->setTheme($formView, $this->formTheme);
    }

    abstract protected function execute(RequestParameters $parameters): array;

    abstract protected function configureForm(FormBuilderInterface $builder, RequestParameters $parameters): void;

}
