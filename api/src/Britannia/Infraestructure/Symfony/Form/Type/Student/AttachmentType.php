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


use Britannia\Domain\Entity\Student\Attachment\Attachment;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Service\FileUpload\AttachmentUploader;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class AttachmentType extends AbstractCompoundType
{
    /**
     * @var AttachmentUploader
     */
    private AttachmentUploader $uploader;

    public function __construct(AttachmentUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, [
                'required' => false,
                'label' => false
            ])
            ->add('upload', FileType::class, [
                'required' => false,
                'label' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2049k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
                'attr' => [
                    'accept' => 'application/pdf'
                ]
            ])
            ->add('path', HiddenType::class, [
                'required' => false
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attachment::class
        ]);

        $resolver->setRequired('student');
        $resolver->setAllowedTypes('student', [Student::class]);
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        if (!empty($view['path']->vars['value'])) {
            $relative = $view['path']->vars['value'];

            $info = $this->uploader->fileInfo($relative);
            $href = $this->uploader->downloadUrl($relative);;

            $view->vars['info'] = $info;
            $view->vars['href'] = $href;
        }

        parent::finishView($view, $form, $options);
    }


    public function customMapping(array $data)
    {
        $pathToFile = $data['path'];
        if (empty($pathToFile)) {
            return null;
        }

        $student = $this->getOption('student');
        $info = $this->uploader->fileInfo($pathToFile);
        $description = $data['description'];

        return Attachment::make($student, $info, $description);
    }
}
