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

namespace Britannia\Domain\VO\Attachment;


use Exception;
use SplFileInfo;

final class FileInfo
{
    /**
     * @var string
     */
    private string $absolutePath;

    /**
     * @var string
     */
    private string $path;
    /**
     * @var string
     */
    private $mimeType;
    /**
     * @var int
     */
    private int $size;
    /**
     * @var string
     */
    private string $humanReadableSize;


    public static function make(string $dirname, string $relative): ?self
    {
        $filename = sprintf('%s/%s', $dirname, $relative);
        if (!file_exists($filename)) {
            return null;
        }

        $fileInfo = new SplFileInfo($filename);
        return new self($fileInfo, $relative);
    }

    private function __construct(SplFileInfo $fileInfo, string $pathToFile)
    {
        $this->assert($fileInfo);

        $this->absolutePath = $fileInfo->getPathname();

        $this->path = $pathToFile;
        $this->baseName = basename($pathToFile);
        $this->mimeType = mime_content_type($fileInfo->getRealPath());
        $this->size = $fileInfo->getSize();
        $this->humanReadableSize = $this->formatSize($this->size);
    }

    private function assert(SplFileInfo $fileInfo)
    {
        if (!$fileInfo->isFile()) {
            $message = sprintf('La ruta "%s" no es un fichero valido', $fileInfo->getPathname());
            throw new Exception($message);
        }
    }

    private function formatSize(int $size, int $precision = 1)
    {
        $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision) . $units[$i];
    }

    /**
     * @return string
     */
    public function absolutePath(): string
    {
        return $this->absolutePath;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function baseName(): string
    {
        return $this->baseName;
    }

    /**
     * @return string
     */
    public function mimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function humanReadableSize(): string
    {
        return $this->humanReadableSize;
    }

    public function addPrefix(string $prefix): string
    {
        return sprintf('%s/%s', ...[
            rtrim($prefix, '/ '),
            $this->path
        ]);
    }
}
