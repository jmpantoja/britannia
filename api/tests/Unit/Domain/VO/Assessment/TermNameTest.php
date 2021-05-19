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

namespace Britannia\Tests\Unit\Domain\VO\Assessment;

use Britannia\Domain\VO\Assessment\TermName;
use PHPUnit\Framework\TestCase;

class TermNameTest extends TestCase
{

    public function testIsSecond()
    {

        $termName = TermName::SECOND_TERM();

        $this->assertTrue($termName->isSecond());

    }
}
