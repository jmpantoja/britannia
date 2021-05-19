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

namespace Britannia\Domain\Entity\Attachment;

use Britannia\Domain\VO\Attachment\FileInfo;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

abstract class Attachment implements Comparable
{
    use ComparableTrait;
    use AggregateRootTrait;

    /**
     * @var AttachmentId
     */
    private $id;

    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $basename;
    /**
     * @var string
     */
    private $mimeType;
    /**
     * @var int
     */
    private $size;
    /**
     * @var string
     */
    private $humanReadableSize;
    /**
     * @var string
     */
    private $description;
    /**
     * @var CarbonImmutable
     */
    private $createdAt;
    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    protected function __construct(FileInfo $info, ?string $description)
    {
        $this->id = new AttachmentId();

        $this->createdAt = CarbonImmutable::now();

        $this->update($info, $description);
    }


    public function update(FileInfo $info, ?string $description): self
    {
        $this->path = $info->path();
        $this->basename = $info->baseName();
        $this->mimeType = $info->mimeType();
        $this->size = $info->size();
        $this->humanReadableSize = $info->humanReadableSize();
        $this->description = $description;

        $this->updatedAt = CarbonImmutable::now();

        return $this;
    }

    /**
     * @return AttachmentId
     */
    public function id(): AttachmentId
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function description(): ?string
    {
        return $this->description;
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


    public function __toString()
    {
        return $this->description();
    }

    public function redate(?CarbonImmutable $createdAt, ?CarbonImmutable $updatedAt): self
    {
        $this->createdAt = $createdAt ?? $this->createdAt;
        $this->updatedAt = $updatedAt ?? $createdAt ?? $this->updatedAt;

        return $this;
    }
}
