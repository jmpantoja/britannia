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
use Symfony\Component\Validator\Constraints\NotBlank;

class TimeType extends AbstractType
{
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\TimeType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = array_merge([
            'style' => 'padding: 0 6px',
        ], (array)$options['attr']);

        $view->vars['attr'] = $attr;

        $view->vars['datepicker_use_button'] = null;
        $view->vars['post_icon'] = $options['post_icon'];
        parent::buildView($view, $form, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'required' => false,
            'post_icon' => 'fa fa-clock-o',
        ]);

        $resolver->setNormalizer('constraints', function (OptionsResolver $resolver) {
            $required = $resolver['required'];
            if (!$required) {
                return [];
            }

            return [
                new NotBlank()
            ];
        });

        parent::configureOptions($resolver);
    }


    public function getBlockPrefix()
    {
        return 'html5_date';
    }
}
