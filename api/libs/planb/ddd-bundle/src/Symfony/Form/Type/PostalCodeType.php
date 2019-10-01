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

namespace PlanB\DDDBundle\Symfony\Form\Type;


use PlanB\DDD\Domain\VO\PostalCode;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostalCodeType extends AbstractSingleType
{
    public function customOptions(OptionsResolver $resolver)
    {

    }

    public function customMapping(FormDataMapper $mapper)
    {
        $mapper
            ->try(function ($value) {
                return PostalCode::make($value);
            });
    }
}
