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

namespace Britannia\Infraestructure\Symfony\Service\FileUpload;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AttachmentUploader extends FileUploader
{
    private $targetDir;
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $router;

    public function __construct(ParameterBagInterface $parameterBag, UrlGeneratorInterface $router)
    {
        $this->targetDir = $parameterBag->get('attachments_dir');
        $this->router = $router;
    }

    public function targetDir(): string
    {
        return $this->targetDir;
    }

    public function uploadUrl(): string
    {
        $this->router->generate('attachment_upload');
    }

    public function downloadUrl(string $pathToFile = null): string
    {
        return $this->router->generate('attachment_download', [
            'path_to_file' => $pathToFile
        ]);
    }


}
