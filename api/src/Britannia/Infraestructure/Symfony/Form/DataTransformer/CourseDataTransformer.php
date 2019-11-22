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

namespace Britannia\Infraestructure\Symfony\Form\DataTransformer;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDDBundle\Sonata\ModelManager;
use Symfony\Component\Form\DataTransformerInterface;

class CourseDataTransformer implements DataTransformerInterface
{


    /**
     * @var Student $student
     */
    private $student;

    /**
     * @var ModelManager $modelManager
     */
    private $modelManager;

    public function __construct(Student $student, ModelManager $modelManager)
    {
        $this->student         = $student;
        $this->modelManager = $modelManager;
    }

    public function transform($data)
    {
        if (!is_null($data)) {
            $results = [];

            /** @var StudentCourse $studentCourse */
            foreach ($data as $studentCourse) {
                $results[] = $studentCourse->getCourse();
            }

            return $results;
        }

        return $data;
    }

    public function reverseTransform($courses)
    {
        $results  = new ArrayCollection();
        $position = 0;

        /** @var Expectation $course */
        foreach ($courses as $studentCourse) {

//            $results->add($studentCourse);
        }

//
//        $qb   = $this->modelManager->getEntityManager()->createQueryBuilder();
//        $expr = $this->modelManager->getEntityManager()->getExpressionBuilder();
//
//        $userHasExpectationsToRemove = $qb->select('entity')
//            ->from($this->getClass(), 'entity')
//            ->where($expr->eq('entity.user', $this->student->getId()))
//            ->getQuery()
//            ->getResult();
//
//        foreach ($userHasExpectationsToRemove as $studentCourse) {
//            $this->modelManager->delete($studentCourse, false);
//        }
//
//        $this->modelManager->getEntityManager()->flush();

        return $results;
    }
}

