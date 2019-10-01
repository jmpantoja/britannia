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


use Britannia\Infraestructure\Symfony\Service\Security\RoleService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType //implements ChoiceLoaderInterface
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
            'expanded' => true
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
