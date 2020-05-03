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


use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Validator;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use PlanB\DDDBundle\Symfony\Form\Type\ToggleType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntensiveType extends AbstractSingleType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ToggleType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'on_text' => 'SI',
            'off_text' => 'NO',
        ]);
    }

    public function transform($value)
    {
        if($value instanceof Intensive){
            return $value->isIntensive();
        }

        return parent::transform($value);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        if ($data === true) {
            return Intensive::INTENSIVE();
        }

        return Intensive::NOT_INTENSIVE();
    }
}
