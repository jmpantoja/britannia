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

namespace Britannia\Infraestructure\Symfony\Admin\Issue\Filter;


use Britannia\Domain\Entity\Staff\StaffMember;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

final class IssueFormType extends AbstractCompoundType
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recipient', ChoiceType::class, [
            'label' => 'Autor / Destinatario',
            'choices' => $this->recipientChoices()
        ]);

        $builder->add('status', ChoiceType::class, [
            'label' => 'Estado',
            'choices' => [
                'Todos' => 0,
                'No Leidos' => 1,
                'Leidos' => 2
            ]
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {

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
        return $data;
    }

    /**
     * @return array
     */
    private function recipientChoices(): array
    {
        $choices = [
            'Todos los mios' => 0,
            'Creados por mi' => 1,
            'Dirigidos a mi' => 2
        ];

        $user = $this->user();
        if ($user->isManager()) {
            $choices['Del resto de usuarios'] = 3;
        }

        return $choices;
    }

    private function user(): StaffMember
    {
        return $this->security->getUser();
    }
}
