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

namespace Britannia\Infraestructure\Symfony\Form\Type\Mark;


use Britannia\Domain\VO\Mark\MarkList;
use Britannia\Domain\VO\Mark\SetOfSkills;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitHasMarksType extends AbstractCompoundType
{

    private $skills;

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var MarkList $data */
        $data = $options['data'];

        foreach ($this->skills as $key => $name) {
            $name = strtolower($name);
            $builder->add($name, MarkType::class, [
                'label' => false,
                'required' => false,
                'data' => $data->get($name),
            ]);
        }

        if (!$options['is_total']) {
            return;
        }
        $builder->add('average', MarkType::class, [
            'label' => false,
            'required' => false,
            'data' => $data->get('average'),
        ]);

    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('is_total');
        $resolver->setAllowedTypes('is_total', ['bool']);

        $resolver->setDefaults(['skills' => null]);
        $resolver->setNormalizer('skills', function (OptionsResolver $resolver) {
            return $this->skills = $resolver['data']->skills();
        });

        $resolver->setNormalizer('disabled', function (OptionsResolver $resolver) {
            return $resolver['is_total'];
        });
    }

    public function buildConstraint(array $options): ?Constraint
    {

        return null;
    }

    public function customMapping(array $data)
    {
        return MarkList::make($this->skills, $data);
    }

}
