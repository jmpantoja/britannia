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


use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Message\Message;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Domain\VO\Message\MessageMailer;
use Carbon\CarbonImmutable;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;
use Symfony\Component\Security\Core\Security;

final class MessageMapper extends AdminMapper
{

    /**
     * @var Message
     */
    private Message $subject;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
        parent::__construct();
    }


    protected function className(): string
    {
        return Message::class;
    }

    public function setSubject(Message $message)
    {
        $this->subject = $message;
        return $this;
    }

    protected function create(array $values): object
    {
        $dto = $this->makeDto($values);
        if ($this->subject instanceof Message\Email) {
            return Message\Email::make($dto);
        }
        return Message\Sms::make($dto);
    }

    protected function update($object, array $values): object
    {
        $dto = $this->makeDto($values);

        return $object->update($dto);
    }

    private function makeDto(array $values)
    {
        $values['createdBy'] = $this->security->getUser();
        $values['schedule'] = CarbonImmutable::make($values['schedule']);
        $values['students'] = StudentList::collect($values['students']);
        $values['courses'] = CourseList::collect($values['courses']);

        if ($this->subject instanceof Message\Email) {
            return Message\EmailDto::fromArray($values);
        }

        return Message\SmsDto::fromArray($values);
    }
}
