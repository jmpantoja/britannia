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


use Britannia\Domain\VO\Attachment\FileInfo;

final class FileUploadResponse
{

    /**
     * @var bool
     */
    private bool $success;

    /**
     * @var FileInfo
     */
    private FileInfo $info;

    /**
     * @var string
     */
    private string $errorMessage;

    public static function success(FileInfo $fileInfo): self
    {
        return (new self())
            ->setFileInfo($fileInfo);
    }

    public static function error(string $errorMessage): self
    {
        return (new self())->setError($errorMessage);
    }

    private function setFileInfo(FileInfo $info): self
    {
        $this->success = true;
        $this->info = $info;

        return $this;
    }

    private function setError(string $errorMessage): self
    {
        $this->success = false;
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function toArray(): array
    {
        if ($this->success) {
            return $this->toSuccessArray();
        }

        return $this->toErrorArray();
    }

    private function toSuccessArray(): array
    {
        return [
            'success' => true,
            'path' => $this->info->path(),
            'name' => $this->info->baseName(),
            'size' => $this->info->humanReadableSize()
        ];
    }

    private function toErrorArray(): array
    {
        return [
            'success' => false,
            'message' => $this->errorMessage,
        ];
    }

}
