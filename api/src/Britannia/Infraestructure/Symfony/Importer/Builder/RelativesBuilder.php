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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Doctrine\Common\Collections\ArrayCollection;

class RelativesBuilder extends BuilderAbstract
{
    private const TYPE = 'Familiar';

    /** @var Student */
    private $student;

    private $relatives;

    public function initResume(array $input): Resume
    {
        $title = sprintf('%s %s', ...[
            $input['nombre'],
            $input['apellidos'],
        ]);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withId($id): self
    {
        $this->student = $this->findOneOrNull(Student::class, [
            'oldId' => $id
        ]);

        return $this;
    }

    public function withRelatives(...$oldId)
    {
        $oldId = array_unique($oldId);

        $relatives = array_map(function ($id) {
            return $this->findOneOrNull(Student::class, [
                'oldId' => $id * 1
            ]);
        }, $oldId);

        $relatives = array_filter($relatives);

        $this->relatives = new ArrayCollection($relatives);
    }


    public function build(): ?object
    {
        if (is_null($this->student)) {
            return null;
        }

        $this->student->setRelatives($this->relatives);
        return $this->student;
    }
}
