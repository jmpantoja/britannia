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

namespace Britannia\Domain\VO\SchoolCourse;


use PlanB\DDD\Domain\Enum\Enum;
use PlanB\DDD\Domain\VO\PositiveInteger;

/**
 * @method static self PRE()
 * @method static self EPO()
 * @method static self ESO()
 * @method static self BACH()
 * @method static self FP()
 */
final class SchoolLevel extends Enum
{
    private const PRE = ['name' => 'PreSchool', 'age' => 3];
    private const EPO = ['name' => 'EPO', 'age' => 6];
    private const ESO = ['name' => 'ESO', 'age' => 12];
    private const BACH = ['name' => 'Bachillerato.', 'age' => 16];
    private const FP = ['name' => 'FP', 'age' => 16];

    public function name(): string
    {
        $value = $this->getValue();
        return $value['name'];
    }

    public function age(): PositiveInteger
    {
        $value = $this->getValue();
        return PositiveInteger::make($value['age']);
    }



}
