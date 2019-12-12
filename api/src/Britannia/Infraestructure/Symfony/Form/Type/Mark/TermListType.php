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


use Britannia\Domain\VO\Mark\TermName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermListType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $terms = TermName::getConstants();

        foreach ($terms as $key => $value) {
            $builder
                ->add($key, TermType::class, [
                    'label' => false,
                    'term' => TermName::byName($key)
                ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collection::class
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        $data = array_filter($data);
        return new ArrayCollection($data);
    }

    protected function dataToForms($data, array $forms): void
    {
        foreach ($data as $name => $discount) {
            $form = $forms[$name] ?? null;
            $this->setData($form, $discount);
        }
    }

    /**
     * @param $form
     * @param $term
     */
    protected function setData(?FormInterface $form, $term): void
    {
        if (is_null($form)) {
            return;
        }

        $form->setData($term);
    }


}
