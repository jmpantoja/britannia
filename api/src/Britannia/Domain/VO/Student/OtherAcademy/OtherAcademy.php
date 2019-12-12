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

namespace Britannia\Domain\VO\Student\OtherAcademy;


use Britannia\Domain\Entity\Academy\Academy;

class OtherAcademy
{

    /**
     * @var Academy
     */
    private $academy;

    /**
     * @var NumOfYears
     */
    private $numOfYears;

    private function __construct(Academy $academy, ?NumOfYears $numOfYears)
    {

        $this->setAcademy($academy);
        $this->setNumOfYears($numOfYears);
    }

    /**
     * @param Academy $academy
     * @return OtherAcademy
     */
    private function setAcademy(Academy $academy): self
    {
        $this->academy = $academy;
        return $this;
    }

    /**
     * @param NumOfYears $numOfYears
     * @return OtherAcademy
     */
    private function setNumOfYears(?NumOfYears $numOfYears): self
    {
        $this->numOfYears = $numOfYears;
        return $this;
    }

    public static function make(Academy $academy, ?NumOfYears $numOfYears): self
    {
        return new self($academy, $numOfYears);
    }

    /**
     * @return Academy
     */
    public function getAcademy(): Academy
    {
        return $this->academy;
    }

    /**
     * @return NumOfYears
     */
    public function getNumOfYears(): ?NumOfYears
    {
        return $this->numOfYears;
    }


}
