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


use Britannia\Domain\Repository\JobStatusDiscountStorageInterface;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Britannia\Domain\VO\Student\Job\JobStatus;
use PlanB\DDD\Domain\VO\Percent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Tightenco\Collect\Support\Collection;

class JobStatusDiscountStorage implements JobStatusDiscountStorageInterface
{


    /**
     * @var JobStatusDiscountList
     */
    private $list;

    public function __construct(ParameterBagInterface $parameters)
    {
        $values = $this->getValues($parameters);
        $data = $this->prepare($values);


        $this->list = JobStatusDiscountList::make($data);
    }

    /**
     * @param ParameterBagInterface $parameters
     * @return array
     */
    private function getValues(ParameterBagInterface $parameters): array
    {
        $discount = (array)$parameters->get('discount');
        return $discount['job_status'];
    }

    /**
     * @param $values
     * @param $default
     * @return mixed
     */
    private function prepare(array $values): Collection
    {
        $allowed = $this->getAllowed();

        return collect($values)
            ->only($allowed)
            ->map(function (int $value) {
                return Percent::make($value);
            });
    }

    private function getAllowed(): Collection
    {
        return collect(JobStatus::getDiscountables())
            ->keys()
            ->map(function ($key) {
                return strtolower($key);
            });
    }

    public function getList(): JobStatusDiscountList
    {
        return $this->list;
    }


}
