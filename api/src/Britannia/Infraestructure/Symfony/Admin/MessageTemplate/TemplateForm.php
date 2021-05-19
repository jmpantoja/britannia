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

namespace Britannia\Infraestructure\Symfony\Admin\MessageTemplate;


use Britannia\Domain\Entity\Message\Template;
use Britannia\Infraestructure\Symfony\Form\Type\Message\EmailPurposeType;
use Britannia\Infraestructure\Symfony\Form\Type\Message\MessageMailerType;
use Britannia\Infraestructure\Symfony\Form\Type\Message\TemplateType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class TemplateForm extends AdminForm
{
    public function configure(Template $template)
    {
        $this->dataMapper()->setSubject($template);

        $isEmail = $template instanceof Template\EmailTemplate;

        $this
            ->add('name', null, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank()
                ]
            ]);

        if ($isEmail) {
            $this->configureEmail();
            return;
        }

        $this->configureSms();
    }

    private function configureEmail()
    {
        $this->add('mailer', MessageMailerType::class, [
            'label' => 'Enviar Desde'
        ]);

        $this->add('purpose', EmailPurposeType::class, [
            'label' => 'Función',
        ]);

        $this->add('subject', TextType::class, [
            'label' => 'Asunto',
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $this->add('template', WYSIWYGType::class, [
            'label' => 'Plantilla Email',
            'constraints' => [
                new NotBlank()
            ]
        ]);
    }

    private function configureSms(): void
    {
        $this->add('template', TemplateType::class, [
            'label' => 'Plantilla SMS',
            'constraints' => [
                new NotBlank()
            ]
        ]);
    }


}
