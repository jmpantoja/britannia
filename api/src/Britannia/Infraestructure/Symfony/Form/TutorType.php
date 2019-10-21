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


use Britannia\Domain\Entity\Student\Tutor;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;

use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TutorType extends AbstractSingleType
{

    /**
     * @var ModelManagerInterface
     */
    private $modelManager;

    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    public function getParent()
    {
        return ModelListType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model_manager' => $this->modelManager,
            'class' => Tutor::class,
            'btn_edit' => false,
            'btn_list' => 'Buscar',
            'btn_add' => 'Nuevo',
            'btn_delete' => 'Borrar'
        ]);

        $resolver->setNormalizer('constraints', function (OptionsResolver $resolver, $constraints) {

            if ($resolver['required'] === false) {
                return $constraints;
            }

            $constraints[] = new NotBlank([
                'message' => 'Se necesita al menos un tutor'
            ]);

            return $constraints;
        });
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    /**
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value)
    {
        return $value;

    }


    public function customMapping($data)
    {

        return $data;
    }
}
