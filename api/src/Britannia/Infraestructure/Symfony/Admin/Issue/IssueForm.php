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

namespace Britannia\Infraestructure\Symfony\Admin\Issue;


use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Infraestructure\Symfony\Form\Type\Issue\IssueRecipientsType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

final class IssueForm extends AdminForm
{

    public function configure(Issue $issue)
    {
        $this
            ->add('subject', null, [
                'label' => 'Asunto',

                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('student', null, [
                'label' => 'Alumno',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('issueHasRecipients', IssueRecipientsType::class, [
                'label' => 'Destinatarios',
            ])
            ->add('message', WYSIWYGType::class, [
                'label' => 'Mensaje',
                'required' => false
            ]);
    }

}
