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

namespace Britannia\Infraestructure\Symfony\Form\Type\Assessment;


use Britannia\Domain\Entity\Unit\UnitList;
use Britannia\Domain\VO\Mark\TermDefinition;
use Britannia\Domain\VO\Mark\TermDefinitionList;
use Britannia\Domain\VO\Mark\TermName;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermDefinitionListType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        foreach (TermName::all() as $term) {
            $builder
                ->add($term->getName(), TermDefinitionType::class, [
                    'label' => false,
                    'data' => $options['data']->definitionByTerm($term)
                ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TermDefinitionList::class,
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {

        $data = array_filter($data);
        return TermDefinitionList::collect($data);
    }

    protected function dataToForms($data, array $forms): void
    {
        foreach ($data as $name => $termDefinition) {
            $form = $forms[$name] ?? null;
            $this->setData($form, $termDefinition);
        }
    }

    /**
     * @param $form
     * @param $term
     */
    protected function setData(?FormInterface $form, TermDefinition $term): void
    {
        if (!($form instanceof FormInterface)) {
            return;
        }

        $form->setData($term);
    }


}
