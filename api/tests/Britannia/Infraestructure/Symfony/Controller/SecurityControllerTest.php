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

namespace Britannia\Tests\Infraestructure\Symfony\Controller;

use Britannia\Tests\WebTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use WebTestTrait;

    public function testLoginPageReturn200()
    {
        $client = $this->client();
        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLoginContainsAForm()
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/login');
        $crawler->filter('form');

        $this->assertCount(1, $crawler);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testTryLoginWithoutParamsIsUnsuccessful()
    {
        $client = $this->client();
        $client->request('GET', '/login');

        $client->submitForm('Acceder');

        $client->followRedirect();

        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Token CSRF no válido');
    }
}
