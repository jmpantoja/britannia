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


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\VO\Student\OtherAcademy\OtherAcademy;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherAcademyType extends AbstractCompoundType
{
    /**
     * @var Pool
     */
    private $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }


    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $admin = $options['sonata_admin'];
        $associationAdmin = $this->pool->getAdminByClass(Academy::class);
        $modelManager = $admin->getModelManager();

        $fieldDescription = $this->getAcademyFieldDescription($admin, $associationAdmin);

        $builder
            ->add('academy', ModelType::class, [
                'label' => 'Academia',
                'btn_add' => 'Crear Academia',
                'expanded' => false,
                'placeholder' => 'En ninguna otra',
                'model_manager' => $modelManager,
                'class' => Academy::class,
                'sonata_field_description' => $fieldDescription
            ])
            ->add('numOfYears', NumOfYearsType::class, [
                'label' => 'Duración estudios'
            ]);
    }

    /**
     * @param $admin
     * @param $associationAdmin
     * @return FieldDescription
     */
    protected function getAcademyFieldDescription(AbstractAdmin $admin, AbstractAdmin $associationAdmin): FieldDescription
    {
        $fieldDescription = new FieldDescription();
        $fieldDescription->setMappingType(ClassMetadataInfo::MANY_TO_ONE);
        $fieldDescription->setAdmin($admin);
        $fieldDescription->setAssociationAdmin($associationAdmin);

        return $fieldDescription;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OtherAcademy::class
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {

        if (!($data['academy'] instanceof Academy)) {
            return null;
        }

        return OtherAcademy::make($data['academy'], $data['numOfYears']);
    }


}
