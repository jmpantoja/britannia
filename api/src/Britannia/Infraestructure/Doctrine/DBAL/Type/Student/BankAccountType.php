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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Student;


use Britannia\Domain\VO\BankAccount;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\DDD\Domain\VO\CityAddress;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\Iban;

class BankAccountType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {

        if (!($value instanceof BankAccount)) {
            return null;
        }

        $titular = $value->getTitular();
        $city = $value->getCityAddress();
        $iban = $value->getIban();

        $json = [
            'titular' => $titular,
            'cityAddress' => [
                'city' => $city->getCity(),
                'province' => $city->getProvince()
            ],
            'iban' => $iban->getElectronicFormat(),
            'number' => $value->getNumber()
        ];

        return json_encode($json, JSON_UNESCAPED_UNICODE);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $json = json_decode($value);

        return BankAccount::make(...[
            $json->titular,
            CityAddress::make(...[
                    $json->cityAddress->city,
                    $json->cityAddress->province,
                ]
            ),
            Iban::make($json->iban),
            $json->number
        ]);
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return self::JSON;
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return 'BankAccount';
    }
}
