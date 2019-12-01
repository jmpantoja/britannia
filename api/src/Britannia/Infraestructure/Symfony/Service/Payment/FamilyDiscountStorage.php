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

namespace Britannia\Infraestructure\Symfony\Service\Payment;


use Britannia\Domain\Repository\FamilyDiscountStorageInterface;
use Britannia\Domain\VO\FamilyDiscountList;
use PlanB\DDD\Domain\VO\Percent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FamilyDiscountStorage implements FamilyDiscountStorageInterface
{

    /**
     * FamilyDiscountStorage constructor.
     * @param ParameterBagInterface $parameters
     */
    public function __construct(ParameterBagInterface $parameters)
    {
        $data = (array)$parameters->get('family_discount');

        $discounts = (array)($data['order'] ?? []);
        $increase = (int)($data['increase'] ?? 0);

        $this->list = $this->createList($discounts, $increase);
    }

    /**
     * @param $discounts
     * @param $increase
     * @return array
     */
    private function createList(array $discounts, int $increase): FamilyDiscountList
    {
        $percents = array_map(function (int $percent) {
            return Percent::make($percent);
        }, $discounts);

        $increasePercent = Percent::make($increase);

        return FamilyDiscountList::make(...$percents)
            ->withIncrease($increasePercent);
    }

    public function getList(): FamilyDiscountList
    {
        return $this->list;
    }

}
