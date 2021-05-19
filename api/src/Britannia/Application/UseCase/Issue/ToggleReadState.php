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

namespace Britannia\Application\UseCase\Issue;


use Britannia\Domain\Entity\Issue\Issue;

final class ToggleReadState
{
    /**
     * @var Issue
     */
    private Issue $issue;

    public static function make(Issue $issue): self
    {
        return new self($issue);
    }

    private function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return Issue
     */
    public function issue(): Issue
    {
        return $this->issue;
    }
}
