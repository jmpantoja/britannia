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
use Cocur\Slugify\Slugify;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class FileUploader
{


    protected function calculeName(string $fileName): string
    {
        $slugify = Slugify::create();
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $name = pathinfo($fileName, PATHINFO_FILENAME);
        $name = $slugify->slugify($name);

        return sprintf('%s.%s', $name, $extension);
    }

    protected function calculePath(string $realPath): string
    {
        $hash = md5_file($realPath);

        $first = substr($hash, 0, 3);
        $second = substr($hash, 3, 3);

        return sprintf('%s/%s', ...[
            $first,
            $second
        ]);
    }

    public function makePathAbsolute(string $relative): string
    {
        return sprintf('%s/%s', $this->targetDir(), $relative);
    }

    public function fileInfo($relative): ?FileInfo
    {
        return FileInfo::make($this->targetDir(), $relative);
    }


    protected function handle(callable $callback): ?FileInfo
    {
        try {
            return $callback();
        } catch (Exception $exception) {
            return FileUploadResponse::error($exception->getMessage());
        }
    }

    public function uploadFile(UploadedFile $file): FileUploadResponse
    {
        $info = $this->handle(function () use ($file) {
            return $this->upload($file);
        });

        return FileUploadResponse::success($info);
    }

    public function copyFile(string $pathToFile): ?FileInfo
    {
        return $this->handle(function () use ($pathToFile) {
            return $this->copy($pathToFile);
        });
    }

    protected function upload(UploadedFile $file): FileInfo
    {
        $name = $this->calculeName($file->getClientOriginalName());
        $path = $this->calculePath($file->getRealPath());

        $target = sprintf('%s/%s', $this->targetDir(), $path);
        $file->move($target, $name);

        $pathToFile = sprintf('%s/%s', $path, $name);
        return FileInfo::make($this->targetDir(), $pathToFile);
    }

    protected function copy(string $pathToFile): ?FileInfo
    {
        $fileSystem = new Filesystem();

        if (!$fileSystem->exists($pathToFile)) {
            return null;
        }

        $name = $this->calculeName(basename($pathToFile));
        $path = $this->calculePath($pathToFile);
        $target = sprintf('%s/%s/%s', $this->targetDir(), $path, $name);
        $fileSystem->copy($pathToFile, $target);
        $pathToFile = sprintf('%s/%s', $path, $name);

        return FileInfo::make($this->targetDir(), $pathToFile);

    }

    abstract public function targetDir(): string;

    abstract public function uploadUrl(): string;

    abstract public function downloadUrl(string $pathToFile = null): string;

}
