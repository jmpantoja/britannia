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


use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\Skill;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherSkillExamType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CourseTerm $courseTerm */
        $courseTerm = $options['data'];
        $termList = $courseTerm->termList();

        $builder->add('definition', OtherSkillDefinitionType::class, [
            'data' => $courseTerm,
            'admin' => $options['admin'],
            'skill' => $options['skill']
        ]);

        foreach ($termList as $term) {
            $field = (string)$term->id();
            $builder->add($field, SkillMarkListType::class, [
                'label' => $term->student(),
                'data' => $term,
                'skill' => $options['skill'],
                'error_bubbling' => true,
                'allow_extra_fields' => true

            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseTerm::class,
            'admin' => null
        ]);

        $resolver->setNormalizer('admin', function (OptionsResolver $resolver) {
            if (empty($resolver['sonata_field_description'])) {
                return null;
            }
            return $resolver['sonata_field_description']->getAdmin();
        });

        $resolver->setRequired('skill');
        $resolver->setAllowedTypes('skill', [Skill::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        /** @var CourseTerm $courseTerm */
        $courseTerm = $options['data'];
        $view->vars['dates'] = $courseTerm->otherExams($options['skill']);

        parent::finishView($view, $form, $options);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        unset($data['definition']);
        return TermList::collect($data);
    }
}
