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

namespace PlanB\DDDBundle\Symfony\Form\Type;

use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractSingleType extends AbstractType implements DataTransformerInterface
{
    /**
     * @var array
     */
    protected $options;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->options = $options;
        $builder->addModelTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    protected function isRequired()
    {
        return $this->options['required'] ?? true;
    }

    /**
     * @return bool
     */
    protected function isErrorBubblig(): bool
    {
        return $this->options['error_bubbling'] ?? true;
    }


    protected function resolve($value, callable $callback)
    {
        try {
            return $this->tryResolve($value, $callback);
        } catch (AllOfException $exception) {


            $this->throwException($exception);
        }
    }

    protected function tryResolve($value, callable $callback)
    {
        if (!empty($value)) {
            return $callback($value);
        }

        if (!$this->isRequired()) {
            return null;
        }

        if ($this->isErrorBubblig()) {
            return null;
        }


        $exception = new AllOfException();
        $message = $this->getRequiredErrorMessage();
        $exception->addRelated(new ValidationException($message));

        throw $exception;
    }

    protected function throwException(AllOfException $exception)
    {
        $messages = $exception->getMessages();
        $message = implode("\n", $messages);

        $failure = new TransformationFailedException($message);
        $failure->setInvalidMessage($message);

        throw $failure;
    }

    abstract protected function getRequiredErrorMessage(): string;


}
