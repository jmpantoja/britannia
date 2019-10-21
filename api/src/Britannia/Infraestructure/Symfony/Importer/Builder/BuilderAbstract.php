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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Infraestructure\Symfony\Importer\Resume;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

abstract class BuilderAbstract implements BuilderInterface
{

    use Maker;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public static function make(array $input, EntityManagerInterface $entityManager): self
    {
        return new static($input, $entityManager);
    }

    private function __construct(array $input, EntityManagerInterface $entityManager)
    {
        $this->resume = $this->initResume($input);

        $this->entityManager = $entityManager;
    }


    abstract public function initResume(array $input): Resume;

    protected function findOne(object $entity, array $criteria): ?object
    {
        $founded = null;
        if(!empty($criteria)){
            $founded = $this->entityManager->getRepository(get_class($entity))
                ->findOneBy($criteria);
        }

        if(!is_null($founded) ){
            return $founded;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }


    protected function watchForErrors(ConstraintViolationList $violationList, array $input = null): bool
    {
        if (count($violationList) === 0) {
            return false;
        }

        foreach ($violationList as $violation) {
            $this->resume->addError($violation, $input);
        }

        return true;
    }

    protected function watchForWarnings(ConstraintViolationList $violationList, array $input = null): bool
    {
        if (count($violationList) === 0) {
            return false;
        }
        foreach ($violationList as $violation) {
            $this->resume->addWarning($violation, $input);
        }

        return true;
    }

    protected function hasViolations(ConstraintViolationList $violationList): bool
    {
        return count($violationList) !== 0;
    }


    public function resume(): Resume
    {
        return $this->resume;
    }


    public function isValid(): bool
    {
        return !$this->resume->hasErrors();
    }


}
