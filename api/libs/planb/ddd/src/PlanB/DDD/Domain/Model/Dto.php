<?php


namespace PlanB\DDD\Domain\Model;


abstract class Dto
{
    public static function fromArray(array $values)
    {
        return new static($values);
    }

    protected function __construct(array $values)
    {

        $defaults = $this->defaults();
        $values = array_replace($defaults, $values);

        $properties = array_keys(get_class_vars(static::class));

        foreach ($properties as $name) {
            if (array_key_exists($name, $values)) {
                $this->{$name} = $values[$name];
                unset($values[$name]);
            }
        }
//        if (count($values)) {
//            $message = sprintf('Datos extra: [%s]', implode(', ', array_keys($values)));
//            throw new \InvalidArgumentException($message);
//        }
    }

    protected function defaults(): array
    {
        return [];
    }


}
