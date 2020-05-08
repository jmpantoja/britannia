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

namespace PlanB\DDDBundle\Symfony\Form\Type;


use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WYSIWYGType extends AbstractSingleType
{

    public function getParent()
    {
        return TextareaType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'format' => 'richhtml',
//            'ckeditor_toolbar_icons' => $this->getToolbar()
        ]);
    }

    private function getToolbar(): array
    {
        return [
            1 => [
                'Bold',
                'Italic',
                'Underline',
                '-', 'NumberedList', 'BulletedList',
                '-', 'Link', 'Unlink'
            ],
            2 => ['Maximize'],
        ];
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return (string)$data;
    }
}
