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
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Doctrine\DBAL\Type\Issue\IssueRecipientIdType;
use Britannia\Infraestructure\Symfony\Form\Type\Issue\IssueRecipientsType;
use Doctrine\ORM\EntityRepository;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class IssueForm extends AdminForm
{

    public function configure(Issue $issue)
    {
        $this
            ->add('subject')
            ->add('message')
            ->add('student')
            ->add('issueHasRecipients', IssueRecipientsType::class, [

            ]);
    }

}
