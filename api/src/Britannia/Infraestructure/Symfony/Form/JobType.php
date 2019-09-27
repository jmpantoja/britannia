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

namespace Britannia\Infraestructure\Symfony\Form;


use Britannia\Domain\VO\Job;
use Britannia\Domain\VO\JobStatus;
use PlanB\DDDBundle\Symfony\Form\FormMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType implements DataMapperInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Profesión'
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'label' => 'Situación Laboral',
                'choice_loader' => new CallbackChoiceLoader(function () {
                    $values = array_flip(JobStatus::getConstants());
                    return array_merge(['' => ''], $values);
                })
            ])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'compound' => true,
            'data_class' => Job::class,
            'empty_data' => null,
            'error_bubbling' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'britannia.job';
    }


    /**
     * Maps the view data of a compound form to its children.
     *
     * @param Job $data View data of the compound form being initialized
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);

        $forms['name']->setData($data ? $data->getName() : null);
        $forms['status']->setData($data ? $data->getStatus() : null);
    }

    /**
     * Maps the model data of a list of children forms into the view data of their parent.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param mixed $viewData The compound form's view data that get mapped
     *                                               its children model data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapFormsToData($forms, &$data)
    {
        $reflection = FormMapper::make($forms, Job::class);

        $data = $reflection->resolve(function ($values) {
            return Job::make(...[
                (string)$values['name'],
                JobStatus::byName($values['status'])
            ]);
        });
    }
}
