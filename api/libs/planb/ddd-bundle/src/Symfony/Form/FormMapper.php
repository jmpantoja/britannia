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

namespace PlanB\DDDBundle\Symfony\Form;


use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormError;

class FormMapper
{
    /**
     * @var array|Form[]
     */
    private $forms;


    /**
     * @var array
     */
    private $values = [];

    /**
     * @var array
     */
    private $original = [];

    /**
     * @var ReflectionDoubleMaker
     */
    private $maker;


    /**
     * @var bool
     */
    private $required = true;

    /**
     * @var string
     */
    private $requiredErrorMessage = 'El valor es requerido';


    public static function make(\RecursiveIteratorIterator $forms, array $defaults = []): self
    {
        $forms = iterator_to_array($forms);
        return new self($forms, $defaults);
    }

    /**
     * FormReflectionUtilities constructor.
     *
     * @param Form[] $forms
     * @param array $defaults
     */
    private function __construct(array $forms, array $defaults = [])
    {
        $this->forms = $forms;
        $this->maker = new ReflectionDoubleMaker();

        foreach ($forms as $name => $form) {

            $default = $defaults[$name] ?? null;
            $this->values[$name] = $form->getData() ?? $default;
            $this->original[$name] = $this->values[$name];
        }
    }

    /**
     * @return string
     */
    public function getRequiredErrorMessage(): string
    {
        return $this->requiredErrorMessage;
    }

    /**
     * @param string $errorMessage
     * @return FormMapper
     */
    public function setRequiredErrorMessage(string $errorMessage): FormMapper
    {
        $this->requiredErrorMessage = $errorMessage;
        return $this;
    }


    /**
     * @param bool $required
     * @return FormMapper
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }


    public function cast(string $key, string $className, callable $callback = null): self
    {

        $value = $this->values[$key] ?? null;

        if ($value instanceof $className) {
            $this->values[$key] = $value;
            return $this;
        }

        if (is_callable($callback)) {
            $this->values[$key] = $callback($this->maker, $value, $this->values);
            return $this;
        }

        $this->values[$key] = $this->maker->create($className, [
            $key => $value
        ]);

        return $this;

    }


    public function isEmpty()
    {
        $filtered = array_filter($this->original);
        return count($filtered) === 0;
    }


    public function resolve(callable $callback)
    {
        try {
            return $this->tryResolve($callback);
        } catch (AllOfException $exception) {
            $this->mapException($exception);
        }

    }

    /**
     * @param callable $callback
     * @return mixed
     */
    private function tryResolve(callable $callback)
    {
        if (!$this->isEmpty()) {
            return $callback($this->values);
        }



        if (!$this->isRequired()) {
            return null;
        }

        $exception = new AllOfException();
        $message = $this->getRequiredErrorMessage();
        $exception->addRelated(new ValidationException($message));

        throw $exception;
    }


    /**
     * @param $forms
     * @param $exception
     */
    protected function mapException(AllOfException $exception): void
    {

        $messages = [];
        $matched = false;
        foreach ($exception as $child) {

            $name = $child->getName();
            $message = $child->getMessage();
            $messages[] = $message;

            if (!isset($this->forms[$name])) {
                continue;
            }

            $error = new FormError($message);

            $this->forms[$name]->addError($error);
            $matched = true;
        }

        if(!$matched){

            $message = implode("\n", $messages);
            $failure = new TransformationFailedException($message);
            $failure->setInvalidMessage($message);

            throw $failure;
        }

    }

}
