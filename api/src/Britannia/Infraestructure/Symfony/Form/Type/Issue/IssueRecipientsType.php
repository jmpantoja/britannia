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

namespace Britannia\Infraestructure\Symfony\Form\Type\Issue;


use Britannia\Domain\Entity\Issue\IssueRecipientList;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Doctrine\ORM\EntityRepository;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueRecipientsType extends AbstractSingleType
{

    public function getParent()
    {
        return EntityType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'class' => StaffMember::class,
            'choice_label' => 'fullName',
            'multiple' => true,
            'by_reference' => true,
            'query_builder' => function (EntityRepository $repo) {

                return $repo->createQueryBuilder('f');
            }
        ]);
    }

    /**
     * @param StudentCourse[] $value
     * @return Student[]
     */
    public function transform($value)
    {
        return IssueRecipientList::collect($value)
            ->toRecipientList()
            ->toArray();
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($values)
    {
        return StaffList::collect($values);
    }

}
