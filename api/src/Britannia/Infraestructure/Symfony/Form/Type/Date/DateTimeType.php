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

namespace Britannia\Infraestructure\Symfony\Form\Type\Date;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class DateTimeType extends AbstractType
{

    public function getBlockPrefix()
    {
        return 'html5_datetime';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = array_merge([
            'style' => 'padding: 0 6px',
        ], (array)$options['attr']);

        $view->vars['attr'] = $attr;

        $view->vars['datepicker_use_button'] = null;
        parent::buildView($view, $form, $options);
    }

    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_label' => false,
            'time_label' => false,
            'constraints' => [
                new NotNull()
            ]

        ]);
        parent::configureOptions($resolver);
    }
}
