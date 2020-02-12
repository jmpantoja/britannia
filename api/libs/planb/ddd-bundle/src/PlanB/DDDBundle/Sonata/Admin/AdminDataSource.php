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


use DateTimeInterface;
use MabeEnum\Enum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Source\SourceIteratorInterface;

class AdminDataSource
{

    /**
     * @var ProxyQuery
     */
    private ProxyQuery $query;
    /**
     * @var callable
     */
    private $mapper;

    private function __construct(ProxyQuery $query)
    {

        $this->query = $query;
//        $this->mapper = $mapper;
    }

    public static function make(DatagridInterface $dataGrid): self
    {
        $dataGrid->buildPager();
        $query = $dataGrid->getQuery();
        $query->setMaxResults(5000);

        return new static($query);
    }


    public function build(): SourceIteratorInterface
    {
        $results = $this->query->execute();

        $values = collect($results)
            ->map($this)
            ->toArray();


        $defaults = $this->getDefaults($values);
        $data = $this->fixMissingFields($values, $defaults);

        return new ArraySourceIterator($data);
    }

    /**
     * @param array $values
     * @return array
     */
    private function getDefaults(array $values): array
    {
        $keys = [];
        foreach ($values as $data) {
            $keys[] = array_flip(array_keys($data));
        }

        $keys = array_merge(...$keys);

        $defaults = array_map(fn() => '[no aplica]', $keys);
        return $defaults;
    }


    private function fixMissingFields(array $values, array $defaults = []): array
    {
        $fixed = [];
        foreach ($values as $data) {
            $fixed[] = array_replace($defaults, $data);
        }
        return $fixed;
    }


    protected function parse($value, array $params = []): string
    {

        if (is_bool($value)) {
            return $this->parseBoolean($value, $params);
        }

        if ($value instanceof Enum) {
            return $this->parseEnum($value, $params);
        }

        if ($value instanceof DateTimeInterface) {
            return $this->parseDate($value, $params);
        }


        if (is_iterable($value)) {
            return $this->parseIterable($value, $params);
        }

        return (string)$value;

    }

    /**
     * @param bool $value
     * @param array $params
     * @return string
     */
    private function parseBoolean(bool $value, array $params = []): string
    {
        $params = array_replace([
            'si' => 'Sí',
            'no' => 'No'
        ], $params);

        return $value ? $params['si'] : $params['no'];
    }


    private function parseEnum(Enum $value, array $params)
    {
        return $value->getValue();
    }

    private function parseDate(DateTimeInterface $value, array $params): string
    {
        $params = array_replace([
            'date' => \IntlDateFormatter::MEDIUM,
            'time' => \IntlDateFormatter::NONE
        ], $params);

        return date_to_string($value, $params['date'], $params['time']);


    }

    private function parseIterable(iterable $value, array $params): string
    {
        $params = array_replace([
            'callback' => fn($item) => $item,
        ], $params);

        $callback = $params['callback'];

        return collect($value)
            ->map(function ($item) use ($callback) {
                $value = $callback($item);

                return $this->parse($value);
            })
            ->implode(" | ");

    }


}
