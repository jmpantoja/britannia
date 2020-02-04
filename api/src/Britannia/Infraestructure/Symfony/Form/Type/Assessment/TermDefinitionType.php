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


use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Admin\Mark\MarkAdmin;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TermDefinitionType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $uniqId = $this->getUniqId($options);

        /** @var CourseTerm $courseTerm */
        $courseTerm = $options['data'];

        $unitsWeight = $courseTerm->unitsWeight();




        $builder->add('numOfUnits', NumberType::class, [
            'html5' => true,
            'mapped' => false,
            'data' => $courseTerm->numOfUnits(),
            'attr' => [
                'max' => 3,
                'min' => 0
            ]
        ])->add('courseId', HiddenType::class, [
            'mapped' => false,
            'data' => (string)$courseTerm->courseId()
        ])->add('termName', HiddenType::class, [
            'mapped' => false,
            'data' => $courseTerm->termName()
        ])->add('uniqId', HiddenType::class, [
            'data' => $uniqId
        ])->add('unitsWeight', PercentageType::class, [
            'label' => '% unidades',
            'data' => $unitsWeight
        ])->add('examWeight', PercentageType::class, [
            'disabled' => true,
            'required' => false,
            'label' => '% examen',
            'data' => $unitsWeight->complementary()
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseTerm::class
        ]);

        $resolver->setRequired('admin', [MarkAdmin::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['admin'] = $options['admin'];

        parent::finishView($view, $form, $options);
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
        $termName = TermName::byName($data['termName']);
        $numOfUnits = (int)$data['numOfUnits'];

        return TermDefinition::make(...[
            $termName,
            $data['unitsWeight'],
            $numOfUnits
        ]);
    }

    /**
     * @param array $options
     * @return string
     */
    private function getUniqId(array $options): ?string
    {
        /** @var MarkAdmin $admin */
        $admin = $options['admin'];
        if(!($admin instanceof MarkAdmin)){
            return null;
        }

        return $admin->getUniqid();
    }
}
