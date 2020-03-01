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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Domain\Entity\Student\Attachment\Attachment;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Attachment\FileInfo;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Britannia\Infraestructure\Symfony\Service\FileUpload\AttachmentUploader;
use Britannia\Infraestructure\Symfony\Service\FileUpload\FileUploader;
use Carbon\CarbonImmutable;
use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\CamelCaseToSeparator;
use Laminas\Filter\Word\SeparatorToCamelCase;

final class AttachmentBuilder extends BuilderAbstract
{

    private const TYPE = 'Adjunto';
    /**
     * @var object|null
     */
    private ?object $student;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $pathToFile;
    /**
     * @var \Britannia\Domain\VO\Attachment\FileInfo
     */
    private $info;
    /**
     * @var CarbonImmutable|null
     */
    private ?CarbonImmutable $createdAt;
    /**
     * @var CarbonImmutable|null
     */
    private ?CarbonImmutable $updatedAt;


    public function initResume(array $input): Resume
    {
        $title = sprintf('%s (%s)', ...[
            $input['id'],
            $input['token'],
        ]);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withOldId(string $id): self
    {
        $this->student = $this->findOneOrNull(Student::class, [
            'oldId' => $id
        ]);

        return $this;
    }

    public function withDescription($title, $comment): self
    {
        $description = sprintf('%s %s', $title, $comment);
        $description = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u',], $description);
        $description = preg_replace('/([aA-zZ])([0-9])/', '$1 $2', $description);

        $filter = (new FilterChain())
            ->attach(new SeparatorToCamelCase('_'))
            ->attach(new CamelCaseToSeparator(' '));

        $this->description = $filter->filter($description);
        return $this;
    }

    public function withFilename(string $sourceDir, string $filename, AttachmentUploader $uploader): self
    {
        $pathToFile = sprintf('%s/%s', $sourceDir, $filename);
        $this->info = $uploader->copyFile($pathToFile);

        return $this;
    }

    public function withDates($createdAt, $updateAt): self
    {
        $this->createdAt = CarbonImmutable::make($createdAt);
        $this->updatedAt = CarbonImmutable::make($updateAt);

        return $this;
    }


    public function build(): ?object
    {
        if (!($this->info instanceof FileInfo)) {
            return null;
        }

        return Attachment::make($this->student, $this->info, $this->description)
            ->redate($this->createdAt, $this->updatedAt);
    }


}
