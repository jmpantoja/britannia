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

namespace Britannia\Application\UseCase\StudentReport;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\TermsParametersInterface;
use Britannia\Domain\Service\Report\ReportList;
use Britannia\Domain\Service\Report\StudentRegistrationForm;
use Britannia\Domain\Service\Report\StudentRegistrationFormForStudent;
use Britannia\Infraestructure\Symfony\Service\Assessment\TermParameters;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class GenerateRegistrationFormUseCase implements UseCaseInterface
{

//
//    /**
//     * GenerateRegistrationFormUseCase constructor.
//     */
//    public function __construct(EntityManagerInterface $entityManager)
//    {
//        $this->settings = $entityManager->find(Setting::class, SettingId::ID);
//    }
    /**
     * @var Setting
     */
    private Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function handle(GenerateRegistrationForm $command)
    {
        /** @var Student $student */
        $student = $command->student();

        return ReportList::make($student->name(), [
            StudentRegistrationForm::make($student, $this->setting),
            StudentRegistrationFormForStudent::make($student, $this->setting),
        ]);
    }

}
