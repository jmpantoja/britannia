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


use phpDocumentor\Reflection\Types\Self_;
use Respect\Validation\Exceptions\AllOfException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;

class FormDataMapper
{

    private $data;

    /**
     * @var array
     */
    private $forms;

    /**
     * @var callable
     */
    private $tryCallback;
    /**
     * @var callable
     */
    private $catchCallback;

    /**
     * @var array
     */
    private $options;

    public static function single($value): self
    {
        return (new self($value))
            ->try(function () {
                return null;
            })
            ->catch(function (string $message) {
                return $message;
            });
    }


    public static function compound(\RecursiveIteratorIterator $forms): self
    {
        $forms = iterator_to_array($forms);

        $data = array_map(function ($form) {
            return $form->getData();
        }, $forms);


        return (new self($data, $forms))
            ->try(function () {
                return null;
            })
            ->catch(function (array $messages) {
                return $messages;
            });
    }

    private function __construct($data, array $forms = null)
    {
        $this->data = $data;
        $this->forms = $forms;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    private function isRequired()
    {
        return $this->options['required'] ?? true;
    }


    private function isCompound()
    {
        return is_array($this->forms);
    }

    /**
     * @return bool
     */
    private function ignoreErrors(): bool
    {
        return true === $this->getOption('error_bubbling');
    }


    public function try(callable $callback): self
    {
        $this->tryCallback = $callback;
        return $this;
    }

    public function catch(callable $callback): self
    {
        $this->catchCallback = $callback;
        return $this;
    }

    public function run()
    {
        try {
            return $this->process();
        } catch (AllOfException $exception) {
            $this->addErrorMessages($exception);

        } catch (\Exception $exception) {

            $failure = new TransformationFailedException($exception->getMessage());
            $failure->setInvalidMessage($exception->getMessage());

            throw $failure;
        } catch (\Throwable $exception) {
            $failure = new TransformationFailedException($exception->getMessage());
            $failure->setInvalidMessage('Type Error : ' . $exception->getMessage());

            throw $failure;
        }

    }

    private function process()
    {

        if (!$this->isEmpty()) {
            return call_user_func($this->tryCallback, $this->data);
        }

        if ($this->isRequired()) {
            $message = $this->getOption('required_message');
            throw new \Exception($message);
        }

        return null;
    }


    private function isEmpty()
    {

        $data = (array)$this->data;
        $filtered = array_filter($data);

        return empty($filtered);
    }

    private function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default ?? null;
    }

    /**
     * @param $exception
     */
    private function addErrorMessages($exception): void
    {
        $messages = $this->parseMessages($exception);
        $formatted = call_user_func($this->catchCallback, $messages, $this->data);


        if($this->ignoreErrors()){
            return;
        }

        if ($this->isCompound()) {
            $formatted = array_replace($messages, $formatted);
            $this->addErrors($formatted);
            return;
        }

        $formatted = $formatted ?? $messages;

        $failure = new TransformationFailedException($formatted);
        $failure->setInvalidMessage($formatted);
        throw $failure;
    }

    private function parseMessages(AllOfException $exception)
    {
        if ($this->isCompound()) {
            return $this->parseCompoundMessages($exception);
        }

        return $this->parseSingleMessage($exception);

    }

    private function parseSingleMessage(AllOfException $exception)
    {
        return implode("\n", $exception->getMessages());
    }

    /**
     * @param AllOfException $exception
     * @return array
     */
    private function parseCompoundMessages(AllOfException $exception): array
    {
        $messages = [];
        $related = $exception->getRelated();

        foreach ($related as $child) {
            $name = $child->getName();
            $errors = $child->getMessages();

            $messages[$name] = implode("\n", $errors);
        }

        return $messages;
    }

    private function addErrors($messages)
    {
        if ($this->isCompound()) {
            return $this->addFormsErrors($messages);
        }
    }

    private function addFormsErrors(array $messages)
    {
        foreach ($this->forms as $name => $form) {

            $message = $messages[$name] ?? null;

            $this->addErrorToForm($form, $message);
        }
    }

    private function addErrorToForm(Form $form, ?string $message)
    {
        if (null === $message) {
            return;
        }

        $error = new FormError($message);
        $form->addError($error);
    }
}
