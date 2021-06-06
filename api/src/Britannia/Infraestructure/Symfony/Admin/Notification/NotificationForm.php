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

namespace Britannia\Infraestructure\Symfony\Admin\Notification;


use Britannia\Domain\Entity\Notification\Notification;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class NotificationForm extends AdminForm
{
    public function configure(Notification $notification)
    {
//        $this->add('sasda', TextType::class, [
//            'mapped' => false
//        ]);
    }

}
