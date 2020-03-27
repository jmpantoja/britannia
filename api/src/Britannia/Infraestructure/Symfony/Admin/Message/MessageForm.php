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

namespace Britannia\Infraestructure\Symfony\Admin\Message;


use Britannia\Domain\Entity\Message\Message;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Infraestructure\Doctrine\Repository\CourseRepository;
use Britannia\Infraestructure\Symfony\Form\Type\Message\MessageMailerType;
use Britannia\Infraestructure\Symfony\Form\Type\Message\SmsType;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityRepository;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MessageForm extends AdminForm
{
    public function configure(Message $message)
    {
        $this->dataMapper()->setSubject($message);

        $disabled = $message->hasBeenProcessed();

        $this->add('subject', TextType::class, [
            'label' => 'Asunto',
            'disabled' => $disabled,
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $this->add('students', null, [
            'disabled' => $disabled,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('A')
                    ->andWhere('A.active = :active ')
                    ->setParameter('active', true);
            }
        ]);

        $this->add('courses', null, [
            'label' => 'Cursos',
            'disabled' => $disabled,
            'query_builder' => function (CourseRepository $repository) {
                return $repository->createQueryBuilder('A')
                    ->andWhere('A.timeRange.status = :status ')
                    ->setParameter('status', CourseStatus::ACTIVE());
            }
        ], [
            'admin_code' => 'admin.course'
        ]);

        $this->add('schedule', DateTimePickerType::class, [
            'disabled' => $disabled,
            'label' => 'Fecha de envio',
            'dp_minute_stepping' => 20,
            'dp_use_seconds' => false,
            'data' => $message->schedule() ?? CarbonImmutable::now()->addMinutes(15)->roundMinute(20)
        ]);


        if ($message instanceof Message\Email) {
            $this->add('mailer', MessageMailerType::class, [
                'disabled' => $disabled,
                'label' => 'Enviar desde:'
            ]);

            $this->add('message', WYSIWYGType::class, [
                'disabled' => $disabled,
                'label' => 'Mensaje',
                'format_options' => [
                    'attr' => [
                        'rows' => 40,
                    ],
                ],
            ]);
        } else {

            $this->add('message', SmsType::class, [
                'disabled' => $disabled,
                'label' => 'SMS',
                'constraints' => [
                    new NotBlank()
                ]
            ]);
        }
    }
}
