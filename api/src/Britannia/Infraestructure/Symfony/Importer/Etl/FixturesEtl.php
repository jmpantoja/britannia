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

namespace Britannia\Infraestructure\Symfony\Importer\Etl;


use Britannia\Infraestructure\Symfony\Importer\Report\Reporter;

final class FixturesEtl extends AbstractEtl
{

    public function clean(): void
    {
        $this->truncate('books');
    }

    public function run(Reporter $reporter): void
    {
        $paths = [
            sprintf('%s/../fixtures/fixtures.sql', __DIR__)
        ];

        $this->loadSql(...$paths);
    }


}
