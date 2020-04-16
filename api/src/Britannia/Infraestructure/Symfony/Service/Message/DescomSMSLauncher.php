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
use Britannia\Domain\Service\Message\SmsLauncher;
use PlanB\DDD\Domain\VO\PhoneNumber;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\CurlHttpClient;

final class DescomSMSLauncher extends SmsLauncher
{
    private $user;
    private $password;


    /**
     * DescomSMSLauncher constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $descom = $parameterBag->get('descom');

        $this->url = $descom['url'];
        $this->user = $descom['user'];
        $this->password = $descom['password'];
    }

    public function send(Student $student, string $message): bool
    {
        $client = new CurlHttpClient();

        $phoneNumber = $this->mobilePhoneNumberByStudent($student);

        if (!($phoneNumber instanceof PhoneNumber)) {
            return false;
        }

        $data = [
            'dryrun' => getenv('APP_ENV') != 'prod',
            'messages' => [
                [
                    'to' => [
                        $phoneNumber->getRaw()
                    ],
                    'text' => $message,
                    'senderID' => 'A.BRITANNIA'
                ]
            ]
        ];

        $response = $client->request('POST', $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'DSMS-User' => $this->user,
                'DSMS-Pass' => $this->password
            ],
            'body' => json_encode($data)
        ]);

        return 200 === $response->getStatusCode();
    }
}
