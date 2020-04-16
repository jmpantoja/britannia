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

namespace Britannia\Infraestructure\Symfony\Form\Type\Message;


use Britannia\Infraestructure\Doctrine\Repository\TemplateRepository;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MessageEmailType extends AbstractCompoundType
{
    /**
     * @var \Britannia\Domain\Entity\Message\Template[]
     */
    private array $templates;

    /**
     * SmsType constructor.
     */
    public function __construct(TemplateRepository $templateRepository)
    {
        $choices = [];
        $templates = $templateRepository->findAllEmail();

        foreach ($templates as $template) {
            $id = (string)$template->id();
            $choices[$id] = $template;
        }

        $this->templates = $choices;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('template', ChoiceType::class, [
            'mapped' => false,
            'label' => false,
            'choice_loader' => new CallbackChoiceLoader(function () {
                return $this->templates;
            }),
            'choice_label' => 'name',
            'choice_value' => 'id',
            'placeholder' => 'Elije un mensaje'
        ]);

        $builder->add('message', WYSIWYGType::class, [
            'label' => false,
            'empty_data' => '',
//            'attr' => [
//                'maxlength' => 160,
//                'rows' => 8,
//            ]
        ]);

    }

    protected function dataToForms($data, array $forms): void
    {
        $forms['message']->setData($data);
    }


    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['templates'] = $this->templates;

        parent::finishView($view, $form, $options);
    }


    public function customOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        return $data['message'];
    }
}
