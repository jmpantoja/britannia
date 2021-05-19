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

namespace Britannia\Tests;


use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Tests\Mock\UserSingleton;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Panther\Client;

trait WebTestTrait
{

    private $player;

    private function player(): Player
    {
        if ($this->player instanceof Player) {
            return $this->player;
        }

        /** @var KernelBrowser $client */
        $client = static::createClient();
        self::bootKernel();

        $this->player = Player::make($client, $this::$container);
        return $this->player;
    }

    private function buildUrl(string $url, ...$params): UrlBuilder
    {
        return UrlBuilder::make($url, $params);
    }

    private function service(string $name, bool $allowPrivate = true): object
    {
        $container = $this->container($allowPrivate);
        return $container->get($name);
    }

    /**
     * @param bool $allowPrivate
     * @return mixed
     */
    private function container(bool $allowPrivate = true)
    {
        $container = static::createClient()->getContainer();
        if ($allowPrivate) {
            $container = $container->get('test.service_container');
        }
        return $container;
    }

}
