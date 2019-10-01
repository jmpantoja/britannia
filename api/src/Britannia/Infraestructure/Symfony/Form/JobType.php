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
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Profesión / Estudios'
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'label' => 'Situación Laboral',
                'choice_loader' => new CallbackChoiceLoader(function () {
                    $values = array_flip(JobStatus::getConstants());
                    return array_merge(['' => ''], $values);
                })
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
            'error_bubbling' => false,
            'required' => false,
            'required_message' => 'Se necesita un trabajo y una situación laboral',
        ]);
    }

    public function customMapping(FormDataMapper $mapper)
    {
        $mapper
            ->try(function (array $data) {
                return Job::make(...[
                    $data['name'],
                    $data['status']
                ]);
            });
    }
}

