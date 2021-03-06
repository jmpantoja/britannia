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

namespace Britannia\Infraestructure\Symfony\Form\Type\Staff;


use Britannia\Infraestructure\Symfony\Service\Security\RoleService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class RoleType extends AbstractType
{


    /**
     * @var RoleService
     */
    private $roles;

    public function __construct(RoleService $roles)
    {

        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choice_loader' => function (Options $options) {

                return new IntlCallbackChoiceLoader(function () {
                    return $this->roles->getList();

                });
            },
            'choice_translation_domain' => false,
            'choice_translation_locale' => null,
            'multiple' => true,
            'expanded' => true,
            'constraints' => [
                new Count([
                    'min' => 1,
                    'minMessage' => 'Se necesita al menos un rol'
                ])
            ]
        ]);

        $resolver->setAllowedTypes('choice_translation_locale', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'role';
    }

}
