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

namespace Britannia\Infraestructure\Symfony\Form\Type\Photo;

use Britannia\Domain\Entity\Staff\Photo as StaffPhoto;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Photo as StudentPhoto;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Attachment\FileInfo;
use Britannia\Infraestructure\Symfony\Service\FileUpload\FileUploader;
use Britannia\Infraestructure\Symfony\Service\FileUpload\PhotoUploader;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class PhotoType extends AbstractCompoundType
{
    /**
     * @var FileUploader
     */
    private FileUploader $uploader;

    public function __construct(PhotoUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('upload', FileType::class, [
            'constraints' => [
                new File([
                    'maxSize' => '2049k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image document',
                ])
            ],
            'attr' => [
                'accept' => 'image/*'
            ]
        ]);

        $builder->add('path', HiddenType::class);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('owner');
        $resolver->setAllowedTypes('owner', [Student::class, StaffMember::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars['person'] = $options['owner'];
        $view->vars['uploadUrl'] = $this->uploader->uploadUrl();
        $view->vars['downloadUrl'] = $this->uploader->downloadUrl();

        $view->vars['shape'] = 'shape.jpg';

        parent::finishView($view, $form, $options);
    }


    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data, $photo = null)
    {
        $pathToFile = $data['path'];
        if (empty($pathToFile)) {
            return null;
        }

        $owner = $this->getOption('owner');
        $info = $this->uploader->fileInfo($pathToFile);

        if ($owner instanceof Student) {
            return $this->mappingForStudent($info, $owner, $photo);
        }

        if ($owner instanceof StaffMember) {
            return $this->mappingForStaff($info, $owner, $photo);
        }

        return null;
    }

    private function mappingForStudent(FileInfo $info, Student $student, ?StudentPhoto $photo): StudentPhoto
    {

        if ($photo instanceof StudentPhoto) {
            return $photo->update($info);
        }

        return StudentPhoto::make($student, $info);
    }


    private function mappingForStaff(FileInfo $info, StaffMember $staffMember, ?StaffPhoto $photo): StaffPhoto
    {
        if ($photo instanceof StaffPhoto) {
            return $photo->update($info);
        }

        return StaffPhoto::make($staffMember, $info);
    }
}
