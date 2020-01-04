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

namespace PlanB\DDDBundle\Sonata\Admin;


use Symfony\Component\Security\Core\Security;

abstract class AdminSecurity
{
    /**
     * @var Security
     */
    private Security $security;

    protected function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function currentUser(){
        return $this->security->getUser();
    }
}
