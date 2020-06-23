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

namespace Britannia\Infraestructure\Symfony\Admin\Invoice;


use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;

final class InvoiceRoutes extends AdminRoutes
{

    protected function configure(): void
    {
        $this->add('download_pdf', $this->path('download/pdf'));
        $this->add('send_email', $this->path('send/email'));

        $this->add('download_sepa', 'download/sepa');
    }
}
