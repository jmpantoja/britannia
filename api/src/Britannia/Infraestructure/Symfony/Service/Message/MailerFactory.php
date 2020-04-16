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


use Britannia\Domain\Service\Message\MailerFactoryInterface;
use Britannia\Domain\Service\Message\MailerInterface;
use Britannia\Domain\VO\Message\MessageMailer;
use Exception;
use Swift_Mailer;


final class MailerFactory implements MailerFactoryInterface
{

    /** @var Swift_Mailer[] */
    private $mailers;

    public function addMailer(string $name, Swift_Mailer $mailer, string $from, string $username)
    {
        $this->mailers[$name] = SwiftMailer::make($from, $username, $mailer);
    }

    public function fromMessageMailer(MessageMailer $messageMailer): MailerInterface
    {
        $name = $messageMailer->getName();
        if (isset($this->mailers[$name])) {
            return $this->mailers[$name];
        }
        $message = sprintf('No existe el mailer "%s"', $name);
        throw new Exception($message);

    }
}
