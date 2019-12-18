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

namespace Britannia\Infraestructure\Symfony\Importer\Report;


use Britannia\Infraestructure\Symfony\Importer\Resume;

class PlainTextReport extends FilesystemReport
{
    const WIDTH_COLUMN_KEY = 15;
    const WIDTH_COLUMN_MESSAGE = 55;

    public function error(Resume $resume): void
    {
        $head = $this->parseHead($resume);
        $body = $this->parseBody($resume->getErrors());

        $row = $this->parseRow($head, $body);
        $this->appendToFile($row, 'error.txt');
    }

    protected function parseBody(array $items): string
    {
        $lines = [];

        foreach ($items as $field => $error) {
            $lines[] = $this->parseItemLine($field, $error);
        }

        return implode("\n", $lines);
    }

    protected function parseItemLine(string $key, array $errors)
    {
        $keys = array_fill(0, count($errors), "\t- ");
        $keys[0] = sprintf("\t- %s", $key);


        $lines = array_map(function ($key, $error) {
            $key = str_pad($key, self::WIDTH_COLUMN_KEY, ' ');
            $message = str_pad($error['message'], self::WIDTH_COLUMN_MESSAGE, ' ');

            $data = json_encode($error['data']);

            return sprintf('%s %s %s', ...[
                $key,
                $message,
                $data
            ]);
        }, $keys, $errors);

        return implode("\n", $lines);
    }

    protected function parseRow(string $head, string $body): string
    {
        return <<<eof
$head
$body

eof;
    }

    public function warning(Resume $resume): void
    {
        $head = $this->parseHead($resume);
        $body = $this->parseBody($resume->getWarnings());

        $row = $this->parseRow($head, $body);
        $this->appendToFile($row, 'warning.txt');
    }


}
