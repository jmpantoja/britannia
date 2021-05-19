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

namespace Britannia\Tests\Functional;

use Britannia\Tests\WebTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    use WebTestTrait;

    public function testLoginPageReturn200()
    {
        $response = $this->player()
            ->get('/login')
            ->response();

        $this->assertTrue($response->isSuccessful());
    }

    public function testLoginContainsAForm()
    {
        $player = $this->player()
            ->get('/login');

        $crawler = $player->crawler();
        $response = $player->response();

        $crawler->filter('form');

        $this->assertCount(1, $crawler);
        $this->assertTrue($response->isSuccessful());
    }

}
