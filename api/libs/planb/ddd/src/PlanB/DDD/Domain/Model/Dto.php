<?php


namespace PlanB\DDD\Domain\Model;


use Symfony\Component\Form\Exception\InvalidArgumentException;

abstract class Dto
{
    public static function fromArray(array $values)
    {
        return new static($values);
    }

    private function __construct(array $values)
    {
        $properties = array_keys(get_class_vars(static::class));

        foreach ($properties as $name) {
            $this->{$name} = $values[$name] ?? null;
            unset($values[$name]);
        }

        if (count($values)) {
            $message = sprintf('Datos extra: [%s]', implode(', ', array_keys($values)));
            throw new InvalidArgumentException($message);
        }
    }

}
