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

namespace Britannia\Infraestructure\Symfony\Service\Setting;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Setting\SettingDto;
use Britannia\Domain\Entity\Setting\SettingId;
use Doctrine\ORM\EntityManagerInterface;

final class SettingFactory
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setting = $entityManager->find(Setting::class, SettingId::ID);
    }

    public function __invoke(): Setting
    {
        $dto = SettingDto::fromArray([]);
        return $this->setting ?? Setting::make($dto);
    }
}
