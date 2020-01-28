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


use Britannia\Infraestructure\Symfony\Form\Type\Assessment\OtherSkillExamType;
use Symfony\Component\Form\FormBuilderInterface;

final class AddSkillAction extends MarkAction
{

    protected function execute(RequestParameters $parameters): array
    {
        $course = $parameters->course();
        $courseTerm = $parameters->courseTerm();

        $courseTerm->addSkill(...[
            $parameters->date(),
            $parameters->skill()
        ]);

        $this->modelManager()->update($course);

        return [];
    }

    protected function configureForm(FormBuilderInterface $builder, RequestParameters $parameters): void
    {
        $courseTerm = $parameters->courseTerm();
        $termName = $parameters->termName();
        $skill = $parameters->skill();

        $name = sprintf('%s-%s', $termName, $skill);

        $builder->add($name, OtherSkillExamType::class, [
            'label' => false,
            'mapped' => false,
            'data' => $courseTerm,
            'skill' => $skill
        ]);

    }
}
