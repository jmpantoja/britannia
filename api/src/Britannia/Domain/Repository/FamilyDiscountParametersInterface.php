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

namespace Britannia\Domain\Repository;


use Britannia\Domain\VO\Discount\FamilyDiscountList;

interface FamilyDiscountParametersInterface
{
    public function getList(): FamilyDiscountList;
}
