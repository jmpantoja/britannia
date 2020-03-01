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

namespace Britannia\Domain\Entity\Image;


use Britannia\Domain\Entity\Staff\Photo\Student;
use Britannia\Domain\VO\Attachment\FileInfo;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

abstract class Image implements Comparable
{
    use ComparableTrait;
    use AggregateRootTrait;

    /**
     * @var ImageId
     */
    protected $id;

    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $basename;
    /**
     * @var string
     */
    protected $mimeType;
    /**
     * @var int
     */
    protected $size;
    /**
     * @var string
     */
    protected $humanReadableSize;

    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /**
     * @var CarbonImmutable
     */
    protected $createdAt;
    /**
     * @var CarbonImmutable
     */
    protected $updatedAt;

    protected function __construct(FileInfo $info)
    {
        $this->id = new ImageId();
        $this->createdAt = CarbonImmutable::now();

        $this->update($info);
    }

    public function update(FileInfo $info): self
    {
        $this->path = $info->path();
        $this->basename = $info->baseName();
        $this->mimeType = $info->mimeType();
        $this->size = $info->size();
        $this->humanReadableSize = $info->humanReadableSize();

        $dim = getimagesize($info->absolutePath());
        $this->width = (int)$dim[0];
        $this->height = (int)$dim[1];

        $this->updatedAt = CarbonImmutable::now();

        return $this;
    }

    /**
     * @return ImageId
     */
    public function id(): ImageId
    {
        return $this->id;
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
    public function basename(): string
    {
        return $this->basename;
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

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * @return CarbonImmutable
     */
    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return CarbonImmutable
     */
    public function updatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }

}
