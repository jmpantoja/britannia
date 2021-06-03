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

namespace Britannia\Infraestructure\Symfony\Form\Type\Tutor;


use Britannia\Domain\Entity\Student\Tutor;
use Britannia\Domain\Entity\Student\TutorDto;
use Britannia\Domain\VO\Student\Tutor\ChoicedTutor;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceTutorType extends AbstractCompoundType
{
    /**
     * @var ModelManagerInterface
     */
    private ModelManagerInterface $modelManager;


    /**
     * TutorType constructor.
     */
    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('__search', SelectTutorType::class, [
                'data' => $options['tutor']
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'sonata_help' => 'padre/madre/abuelo/hermano...',
                'data' => $options['description'],
            ])
            ->add('tutor', TutorType::class, [
                'required' => $options['required'],
                'label' => false,
                'data' => $options['tutor'],
                'field' => $options['property_path']
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'description',
            'tutor'
        ]);

        $resolver->addAllowedTypes('description', ['string', 'null']);
        $resolver->addAllowedTypes('tutor', [Tutor::class, 'null']);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['admin'] = $options['sonata_field_description']->getAdmin();
        $view->vars['field_name'] = $options['sonata_field_description']->getName();

        parent::finishView($view, $form, $options);
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return ChoicedTutor::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        $selected = $data['__search'];
        $dto = $data['tutor'];

        if ($dto === null) {
            return null;
        }

        $tutor = $this->makeTutor($dto, $selected);
        $description = $data['description'];

        return ChoicedTutor::make($tutor, $description);
    }

    private function makeTutor(TutorDto $dto, ?Tutor $tutor): Tutor
    {
        if ($tutor instanceof Tutor) {
            $tutor->update($dto);
            return $tutor;
        }

        return Tutor::make($dto);
    }
}
