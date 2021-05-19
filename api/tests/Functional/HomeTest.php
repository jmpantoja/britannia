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

class HomeTest extends WebTestCase
{

    use WebTestTrait;

    public function testHomePageReturns200()
    {
        $response = $this->player()
            ->get('/')
            ->response();

        $this->assertTrue($response->isSuccessful());
    }

}
