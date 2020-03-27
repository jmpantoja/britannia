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

namespace Britannia\Infraestructure\Symfony\Service\Message;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Message\Mailer;
use PlanB\DDD\Domain\VO\Email;
use Swift_Mailer;
use Swift_Message;

final class SwiftMailer extends Mailer
{
    /**
     * @var string
     */
    private string $from;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var Swift_Mailer
     */
    private Swift_Mailer $mailer;

    public static function make(string $from, string $name, Swift_Mailer $mailer)
    {
        return new self($from, $name, $mailer);
    }

    private function __construct(string $from, string $name, Swift_Mailer $mailer)
    {
        $this->from = $from;
        $this->name = $name;
        $this->mailer = $mailer;
    }

    public function send(Student $student, string $message, string $subject): bool
    {
        $recipient = $this->emailByStudent($student);
        if (!($recipient instanceof Email)) {
            return false;
        }

        $message = $this->createMessage($recipient, $message, $subject);

        $failedRecipients = [];
        $this->mailer->send($message, $failedRecipients);

        return empty($failedRecipients);
    }

    /**
     * @param string $message
     * @param string $subject
     * @param $recipient
     * @return Swift_Message
     */
    private function createMessage(Email $recipient, string $message, string $subject): Swift_Message
    {


        return (new Swift_Message($subject))
            ->setFrom([$this->from => $this->name])
            ->setTo([$recipient->getEmail()])
            ->setBody($message)
            ->setContentType('text/html');
    }
}
