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

namespace Britannia\Domain\Service\Report;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\TemplateRepositoryInterface;
use Britannia\Domain\Service\Message\MailerFactoryInterface;
use Britannia\Domain\VO\Message\EmailPurpose;
use Britannia\Infraestructure\Symfony\Controller\Admin\Report\FileDownload;
use Britannia\Infraestructure\Symfony\Service\Message\MailerFactory;

final class ReportEmailSender
{
    /**
     * @var MailerFactory
     */
    private MailerFactory $mailerFactory;
    /**
     * @var FileDownload
     */
    private FileDownload $fileDownload;
    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $templateRepository;

    public function __construct(MailerFactoryInterface $mailerFactory,
                                FileDownload $fileDownload,
                                TemplateRepositoryInterface $templateRepository)
    {
        $this->mailerFactory = $mailerFactory;
        $this->fileDownload = $fileDownload;
        $this->templateRepository = $templateRepository;
    }

    public function send(Student $student, ReportList $reportList, EmailPurpose $purpose): bool
    {
        $template = $this->templateRepository->getByEmailPurpose($purpose);

        $mailer = $this->mailerFactory->fromMessageMailer($template->mailer());
        $files = $this->fileDownload->generateTempFiles($reportList);

        return $mailer->send($student, $template->template(), $template->subject(), $files);
    }

}
