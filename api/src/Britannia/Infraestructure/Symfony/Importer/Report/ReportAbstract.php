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

namespace Britannia\Infraestructure\Symfony\Importer\Report;


use Britannia\Infraestructure\Symfony\Importer\Resume;

abstract class ReportAbstract implements ReportInterface
{

    protected function parseHead(Resume $resume): string
    {
        return sprintf('%s[%s] ** %s', ...[
            $resume->getType(),
            $resume->getId(),
            $resume->getTitle()
        ]);
    }

    public function dump(Resume $resume)
    {
        if ($resume->isSuccessful()) {
            $this->success($resume);
            return;
        }

        if ($resume->hasErrors()) {
            $this->error($resume);
            return;
        }

        $this->warning($resume);
    }

    abstract public function success(Resume $resume): void;

    abstract public function error(Resume $resume): void;

    abstract public function warning(Resume $resume): void;

}
