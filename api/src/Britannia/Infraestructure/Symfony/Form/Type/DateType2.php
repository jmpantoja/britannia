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

namespace Britannia\Infraestructure\Symfony\Form\Type;


use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType2 extends AbstractSingleType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $value = $view->vars['value'];

        $value = CarbonImmutable::make($value);
        $date = null;
        if ($value instanceof CarbonInterface) {
            $date = $value->format('Y-m-d');
        }

        $attr = array_merge([
            'style' => 'padding: 0 6px',
        ], (array)$options['attr']);

        $view->vars['value'] = $date;
        $view->vars['type'] = 'date';
        $view->vars['attr'] = $attr;

        $view->vars['datepicker_use_button'] = null;
        parent::buildView($view, $form, $options);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'post_icon' => 'fa fa-calendar',
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        if ($data instanceof CarbonInterface) {
            return $data;
        }

        return CarbonImmutable::make($data);
    }

    public function getBlockPrefix()
    {
        return 'html5_date';
    }


}
