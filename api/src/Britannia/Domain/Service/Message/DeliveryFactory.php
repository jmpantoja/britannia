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

namespace Britannia\Domain\Service\Message;


use Britannia\Domain\Entity\Message\Message;
use Britannia\Domain\Entity\Message\Message\Email;
use Britannia\Domain\Entity\Message\Message\Sms;
use Exception;


final class DeliveryFactory implements DeliveryFactoryInterface
{
    /**
     * @var MailerFactoryInterface
     */
    private MailerFactoryInterface $mailerFactory;
    /**
     * @var SmsLauncherInterface
     */
    private SmsLauncherInterface $smsLauncher;


    /**
     * DeliveryFactory constructor.
     */
    public function __construct(MailerFactoryInterface $mailerFactory, SmsLauncherInterface $smsLauncher)
    {
        $this->mailerFactory = $mailerFactory;
        $this->smsLauncher = $smsLauncher;
    }

    public function fromMessage(Message $message): DeliveryInterface
    {
        if ($message instanceof Sms) {
            return $this->sms($message);
        }

        if ($message instanceof Email) {
            return $this->email($message);
        }

        $message = sprintf('No hay delivery para %s', get_class($message));
        throw new Exception($message);
    }

    private function email(Email $email): DeliveryInterface
    {
        return $this->mailerFactory->fromMessageMailer($email->mailer());
    }

    private function sms(Sms $sms): DeliveryInterface
    {
        return $this->smsLauncher;
    }


}
