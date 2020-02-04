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

namespace Britannia\Domain\VO\Payment;


use Britannia\Domain\VO\BankAccount\BankAccount;

use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class Payment
{

    use Validable;

    private $mode;

    private $account;

    private function __construct($mode, $account)
    {
        $this->setMode($mode);
        $this->setAccount($account);
    }

    /**
     * @param mixed $mode
     * @return Payment
     */
    private function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @param mixed $account
     * @return Payment
     */
    private function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Payment ([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(PaymentMode $mode, ?BankAccount $account)
    {
        return new self($mode, $account);
    }

    /**
     * @return mixed
     */
    public function getMode(): PaymentMode
    {
        return $this->mode;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

}
