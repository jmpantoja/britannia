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


use PlanB\DDDBundle\Symfony\Form\FormMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

abstract class AbstractCompoundType extends AbstractType implements DataMapperInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;
        $builder->setDataMapper($this);
    }

    /**
     * @param $data
     * @param $forms
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function dataToForms($data, $forms): void
    {
        $forms = iterator_to_array($forms);

        foreach ($forms as $name => $form) {

            $method = sprintf('get%s', ucfirst($name));

            $value = null;
            if (is_callable([$data, $method])) {
                $value = $data->{$method}();
            }

            $form->setData($value);
        }

    }

    protected function isRequired()
    {
        return $this->options['required'] ?? true;
    }


    protected function mapper(\RecursiveIteratorIterator $forms, array $defaults = [])
    {

        return FormMapper::make($forms, $defaults)
            ->setRequired($this->isRequired())
            ->setRequiredErrorMessage($this->getRequiredErrorMessage());
    }

    abstract protected function getRequiredErrorMessage(): string;

}
