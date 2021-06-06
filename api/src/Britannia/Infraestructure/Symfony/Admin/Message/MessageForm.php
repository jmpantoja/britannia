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
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateTimeType;
use Britannia\Infraestructure\Symfony\Form\Type\Date\Validator\DateTime;
use Britannia\Infraestructure\Symfony\Form\Type\Message\MessageEmailType;
use Britannia\Infraestructure\Symfony\Form\Type\Message\MessageMailerType;
use Britannia\Infraestructure\Symfony\Form\Type\Message\MessageSmsType;
use Doctrine\ORM\EntityRepository;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
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

        $this->add('schedule', DateTimeType::class, [
            'disabled' => $disabled,
            'label' => 'Fecha de envio',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ]
        ]);

        if ($message instanceof Message\Email) {
            $this->add('mailer', MessageMailerType::class, [
                'disabled' => $disabled,
                'label' => 'Enviar desde:',
            ]);

            $this->add('message', MessageEmailType::class, [
                'disabled' => $disabled,
                'label' => 'Email',
                'constraints' => [
                    new NotBlank()
                ]
            ]);

        } else {

            $this->add('message', MessageSmsType::class, [
                'disabled' => $disabled,
                'label' => 'SMS',
                'constraints' => [
                    new NotBlank()
                ]
            ]);
        }
    }
}
