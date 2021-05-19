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

namespace Britannia\Infraestructure\Symfony\Form\Type\Student;


use Britannia\Domain\Entity\Attachment\AttachmentList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Service\FileUpload\AttachmentUploader;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AttachmentListType extends AbstractSingleType
{

    /**
     * @var AttachmentUploader
     */
    private AttachmentUploader $uploader;

    public function __construct(AttachmentUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'entry_type' => AttachmentType::class,
            'error_bubbling' => false
        ]);

        $resolver->setRequired('student');
        $resolver->setAllowedTypes('student', [Student::class]);

        $resolver->setNormalizer('entry_options', function (OptionsResolver $resolver) {
            return [
                'student' => $resolver['student']
            ];
        });
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return AttachmentList::collect($data);
    }
}
