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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Mark;


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermListType;
use Symfony\Component\Form\FormBuilderInterface;

final class UpdateMarksAction extends MarkAction
{

    protected function execute(RequestParameters $parameters): array
    {
        $courseTerm = $parameters->courseTerm();
        $termDefinition = $parameters->termDefinition();

        $courseTerm->updateDefintion($termDefinition);
        $courseTerm->termList()
            ->values()
            ->each(fn(Term $term) => $this->modelManager()->update($term));

        return [
            'units' => $courseTerm->units(),
            'skills' => $courseTerm->setOfSkills()
        ];
    }

    protected function configureForm(FormBuilderInterface $builder, RequestParameters $parameters): void
    {
        $name = $parameters->termName()->getName();

        $builder->add($name, TermListType::class, [
            'label' => false,
            'mapped' => false,
            'data' => $parameters->courseTerm()
        ]);
    }
}
