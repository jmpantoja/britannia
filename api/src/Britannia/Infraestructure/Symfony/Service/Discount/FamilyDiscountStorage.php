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

namespace Britannia\Infraestructure\Symfony\Service\Discount;


use Britannia\Domain\Repository\FamilyDiscountStorageInterface;
use Britannia\Domain\VO\Discount\FamilyDiscountList;
use PlanB\DDD\Domain\VO\Percent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Tightenco\Collect\Support\Collection;

class FamilyDiscountStorage implements FamilyDiscountStorageInterface
{

    /**
     * FamilyDiscountStorage constructor.
     * @param ParameterBagInterface $parameters
     */
    const DEFAULT_UPPER_PERCENT = 0;
    const DEFAULT_LOWER_PERCENT = 15;
    const DEFAULT_PERCENT = 5;

    /**
     * @var FamilyDiscountList
     */
    private $list;


    public function __construct(ParameterBagInterface $parameters)
    {
        $values = $this->getValues($parameters);
        $default = $this->getDefault();

        $data = $this->prepare($values, $default);

        $this->list = FamilyDiscountList::make(...[
            $data['upper'],
            $data['lower'],
            $data['default'],
        ]);
    }

    /**
     * @param ParameterBagInterface $parameters
     * @return array
     */
    private function getValues(ParameterBagInterface $parameters): array
    {
        $discount = (array)$parameters->get('discount');
        return $discount['family'];
    }

    /**
     * @return array
     */
    private function getDefault(): array
    {
        return [
            'upper' => self::DEFAULT_UPPER_PERCENT,
            'lower' => self::DEFAULT_LOWER_PERCENT,
            'default' => self::DEFAULT_PERCENT
        ];
    }

    /**
     * @param $values
     * @param $default
     * @return mixed
     */
    private function prepare(array $values, array $default): Collection
    {
        return collect($default)
            ->replace($values)
            ->only(['upper', 'lower', 'default'])
            ->map(function (int $value) {
                return Percent::make($value);
            });
    }


    public function getList(): FamilyDiscountList
    {
        return $this->list;
    }
}
