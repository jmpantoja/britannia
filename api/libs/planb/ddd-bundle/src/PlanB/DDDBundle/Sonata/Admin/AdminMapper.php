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

use Exception;
use PlanB\DDD\Domain\Model\Exception\InvalidTypeException;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Throwable;


abstract class AdminMapper implements DataMapperInterface
{
    private $className;

    protected $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->className = $this->className();
    }

    abstract protected function className(): string;

    public function mapDataToForms($object, $forms)
    {
        $data = $this->obj2array($object);

        $empty = null === $data || [] === $data;

        if (!$empty && !\is_array($data) && !\is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        foreach ($forms as $key => $form) {
            $propertyPath = $form->getPropertyPath();
            $config = $form->getConfig();

            if (!$empty && null !== $propertyPath && $config->getMapped()) {
                $value = array_key_exists($key, $data)
                    ? $data[$key]
                    : $this->propertyAccessor->getValue($object, $key);

                $form->setData($value);
            } else {
                $form->setData($config->getData());
            }
        }
    }

    protected function obj2array($object)
    {
        $values = (array)$object;
        $data = array();

        foreach ($values as $key => $value) {
            $aux = explode("\0", $key);
            $newkey = $aux[count($aux) - 1];
            $data[$newkey] = $value;
        }

        return $data;
    }

    public function mapFormsToData($forms, &$data)
    {

        if (!($data instanceof $this->className)) {
            throw new UnexpectedTypeException($data, $this->className);
        }

        $forms = iterator_to_array($forms);

        $values = array_map(function (FormInterface $form) {
            return $form->getData();
        }, $forms);

        $id = $this->propertyAccessor->getValue($data, 'id');

        if (null === $id) {
            $data = $this->create($values);
            $this->assertType($data);
            return;
        }

        $this->assertType($data);
        $data = $this->update($data, $values);
    }

    /**
     * @param $object
     */
    protected function assertType($object): void
    {
        if (!is_a($object, $this->className(), true)) {
            throw InvalidTypeException::make($object, $this->className());
        }
    }

    abstract protected function create(array $values): object;

    abstract protected function update($object, array $values): object;
}
