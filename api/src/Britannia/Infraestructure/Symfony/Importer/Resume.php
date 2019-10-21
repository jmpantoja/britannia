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

namespace Britannia\Infraestructure\Symfony\Importer;


use MongoDB\BSON\Regex;
use Respect\Validation\Rules\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;

class Resume
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $uuid;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var
     */
    private $warnings = [];

    public static function make(int $id, string $type, string $title): self
    {
        return new self($id, $type, $title);
    }

    private function __construct(int $id, string $type, string $title)
    {

        $this->id = $id;
        $this->type = trim($type);
        $this->title = trim($title);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * @return int
     */
    public function getUuid(): int
    {
        return $this->uuid;
    }

    /**
     * @param int $uuid
     * @return Resume
     */
    public function setUuid(int $uuid): Resume
    {
        $this->uuid = $uuid;
        return $this;
    }


    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function addValue(string $key, $value): Resume
    {
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addError(ConstraintViolation $violation, array $input = null): Resume
    {
        return $this->addViolationToList($this->errors, $violation, $input);
    }


    private function addViolationToList(array &$innerList, ConstraintViolation $violation, array $input = null): self
    {
        $key = $violation->getPropertyPath();
        $message = $violation->getMessage();
        $data = $input ?? $violation->getRoot();

        $innerList[$key] = $innerList[$key] ?? [];
        $innerList[$key][] = [
            'message' => $message,
            'data' => $data
        ];

        return $this;
    }

    public function addWarning(ConstraintViolation $violation, array $input = null): Resume
    {
        return $this->addViolationToList($this->warnings, $violation, $input);
    }

    public function hasErrors(): bool
    {
        return 0 !== count($this->errors);
    }

    public function hasWarnings(): bool
    {
        return 0 !== count($this->warnings);
    }


    public function isSuccessful(): bool
    {
        return !$this->hasErrors() && !$this->hasWarnings();
    }

}
