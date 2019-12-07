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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\TimeTable;


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;

use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassRoomType extends AbstractSingleType
{
    private $classRoomList = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        /** @var ClassRoom[] $classRoomList */
        $classRoomList = $entityManager->getRepository(ClassRoom::class)
            ->findAll();


        foreach ($classRoomList as $classRoom) {
            $id = (string)$classRoom->getId();
            $name = $classRoom->getName();

            $this->classRoomList[$name] = $id;
        }

        ksort($this->classRoomList);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Aula',
            'choice_loader' => new CallbackChoiceLoader(function () {
                return $this->classRoomList;
            }),
            'required' => false,
            'attr' => array('style' => 'max-width: 150px')
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function transform($value)
    {
        if ($value instanceof ClassRoomId) {
            return (string)$value;
        }

        return $value;
    }


    public function customMapping($data)
    {

        if ($data instanceof ClassRoomId) {
            return $data;
        }

        return new ClassRoomId($data);
    }
}
