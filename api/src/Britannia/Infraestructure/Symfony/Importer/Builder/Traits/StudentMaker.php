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

namespace Britannia\Infraestructure\Symfony\Importer\Builder\Traits;


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Academy\AcademyDto;
use Britannia\Domain\Entity\School\School;
use Britannia\Domain\Entity\School\SchoolDto;
use Britannia\Domain\Entity\Student\Tutor;
use Britannia\Domain\Entity\Student\TutorDto;
use Britannia\Domain\VO\BankAccount\BankAccount;
use Britannia\Domain\VO\Payment\Payment;
use Britannia\Domain\VO\Payment\PaymentMode;
use Britannia\Domain\VO\Student\Job\Job;
use Britannia\Domain\VO\Student\Job\JobStatus;
use Britannia\Domain\VO\Student\OtherAcademy\NumOfYears;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\CityAddress;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\Iban;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDD\Domain\VO\PostalCode;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

trait StudentMaker
{
    private $defaultName = 'NOMBRE DESCONOCIDO';
    private $defaultLastName = 'APELLIDOS DESCONOCIDOS';

    public function toPayment(array $input): ?Payment
    {
        $mode = $this->toPaymentMode($input['mode'] * 1);

        $account = null;
        if (!$mode->isCash() || !empty($input['iban'])) {
            $account = $this->toBankAccount($input);
        }

        $violations = Payment::validate([
            'mode' => $mode,
            'account' => $account
        ]);


        if ($this->watchForWarnings($violations, $input)) {
            return Payment::make(PaymentMode::CASH(), null);
        }

        return Payment::make($mode, $account);
    }

    private function toPaymentMode(int $mode): PaymentMode
    {
        switch ($mode) {
            case 0:
                return PaymentMode::CASH();
            case 1:
                return PaymentMode::DAY_1();
            case 2:
                return PaymentMode::DAY_10();
        }

        return PaymentMode::CASH();
    }

    private function toBankAccount(array $input): ?BankAccount
    {
        $data = [
            'titular' => $input['titular'],
            'cityAddress' => $this->toCityAddress($input),
            'iban' => $this->toIban($input['iban']),
            'number' => $input['number'] * 1,
        ];


        $violations = BankAccount::validate($data);

        if ($this->hasViolations($violations)) {
            return null;
        }

        return BankAccount::make(...[
            $data['titular'],
            $data['cityAddress'],
            $data['iban'],
            $data['number'],
        ]);
    }

    private function toCityAddress(array $input): ?CityAddress
    {
        $violations = CityAddress::validate($input);

        if ($this->hasViolations($violations)) {
            return null;
        }

        return CityAddress::make(...[
            $input['city'],
            $input['province'],
        ]);

    }

    abstract protected function hasViolations(ConstraintViolationList $violationList): bool;

    private function toIban(string $iban): ?Iban
    {
        $violations = Iban::validate($iban);

        if ($this->hasViolations($violations)) {
            return null;
        }

        return Iban::make($iban);
    }

    abstract protected function watchForWarnings(ConstraintViolationList $violationList, array $input = null): bool;

    public function toDate(string $date): ?CarbonImmutable
    {

        if (empty($date)) {
            return null;
        }

        $validator = Validation::createValidator();

        $violations = $validator->validate($date, [
            new DateTime([
                'message' => "La fecha no es correcta"
            ])
        ]);

        if ($this->watchForWarnings($violations)) {
            return null;
        }

        return CarbonImmutable::make($date);
    }

    abstract protected function findOneOrNull(string $className, array $criteria): ?object;

    /**
     * @param string $time
     * @return array
     */
    protected function toNumOfYears(string $time): ?NumOfYears
    {
        if ($time === 'AÑO Y MEDIO') {
            $time = '1';
        }

        if (!is_numeric($time)) {
            return null;
        }

        $time = preg_replace('/\D/', '', $time);

        $numOfYears = null;
        switch ($time * 1) {
            case 1:
                $numOfYears = NumOfYears::ONE_YEAR();
                break;
            case 2:
                $numOfYears = NumOfYears::TWO_YEARS();
                break;
            case 3:
                $numOfYears = NumOfYears::THREE_YEARS();
                break;
            case 4:
                $numOfYears = NumOfYears::FOUR_YEARS();
                break;
            case 5:
                $numOfYears = NumOfYears::FIVE_YEARS_OR_MORE();
                break;
        }

        return $numOfYears;
    }

    /**
     * @param string $name
     * @return Academy|null|object
     */
    protected function toAcademy(string $name)
    {
        $dto = AcademyDto::fromArray([
            'name' => $name
        ]);

        $academy = Academy::make($dto);
        $academy = $this->findOneOrCreate($academy, [
            'name' => $name
        ]);
        return $academy;
    }

    abstract protected function findOneOrCreate(object $entity, array $criteria): ?object;

    /**
     * @param string $name
     * @return School|null
     */
    protected function toSchool(string $name): ?School
    {
        $dto = SchoolDto::fromArray([
            'name' => $name
        ]);

        $school = School::make($dto);

        return $this->findOneOrCreate($school, [
            'name' => $name
        ]);
    }

    protected function toTutor(array $data, string $name): ?Tutor
    {
        $data['firstName'] = $this->cleanName((string)$data['firstName']);
        $data['lastName'] = $this->cleanName((string)$data['lastName']);

        $cleanFullName = $this->cleanFullName($data['firstName'], $data['lastName']);

        if ($cleanFullName['firstName'] == $this->defaultName) {
            $cleanFullName['firstName'] = sprintf('tutor-name (%s)', trim($name));
        }

        if ($cleanFullName['lastName'] == $this->defaultLastName) {
            $cleanFullName['lastName'] = sprintf('tutor-lastname (%s)', trim($name));
        }

        $fullName = $this->toFullName([
            'firstName' => (string)$cleanFullName['firstName'],
            'lastName' => (string)$cleanFullName['lastName']
        ]);

        $dni = $this->toDni((string)$data['dni']);

        $address = $this->toPostalAddress(...[
            (string)$data['address'],
            (string)$data['postalCode'],
        ]);

        $jobStatus = $this->toJobStatus((string)$data['jobStatus']);

        $job = null;
        if (!is_null($jobStatus)) {
            $job = Job::make($data['jobName'], $jobStatus);
        }

        $phoneNumbers = $this->toPhoneNumbers((string)$data['phone'], (string)$data['phone2'], (string)$data['extra']);

        $emails = [];
        $emails[] = $this->toEmail((string)$data['email']);

        if (false !== strpos((string)$data['extra'], '@')) {
            $emails[] = $this->toEmail((string)$data['extra']);
        }

        $emails = array_filter($emails);

        $dto = TutorDto::fromArray([
            'fullName' => $fullName,
            'dni' => $dni,
            'address' => $address,
            'job' => $job,
            'phoneNumbers' => $phoneNumbers,
            'emails' => $emails,
        ]);

        $tutor = Tutor::make($dto);

        return $this->findOneOrCreate($tutor, [
            'fullName.fullName' => $fullName->getFullName()
        ]);

    }

    private function cleanName(string $name): ?string
    {
        $name = preg_replace("/[[:punct:]]|\d/u", '', $name);
        return trim($name);
    }

    private function toFullName(array $input): ?FullName
    {
        $input = $this->cleanFullName((string)$input['firstName'], (string)$input['lastName']);

        if (empty($input['firstName']) || empty($input['lastName'])) {
            return null;
        }

        $violations = FullName::validate($input);

        if ($this->watchForErrors($violations)) {
            return null;
        }

        return FullName::make(...[
            $input['firstName'],
            $input['lastName'],
        ]);
    }

    private function cleanFullName(string $firstName, string $lastName): array
    {

        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if (empty($firstName)) {
            $firstName = $lastName;
            $lastName = '';
        }

        if (empty($lastName)) {
            $pieces = preg_split('/( |,)/', $firstName);
            $pieces = array_filter($pieces);

            $lastName = array_pop($pieces);
            $firstName = implode(' ', $pieces);
        }

        $firstName = str_replace([','], '', $firstName);
        $lastName = str_replace([','], '', $lastName);


        $firstName = empty($firstName) ? $this->defaultName : $firstName;
        $lastName = empty($lastName) ? $this->defaultLastName : $lastName;

        return [
            'firstName' => $firstName,
            'lastName' => $lastName
        ];
    }

    abstract protected function watchForErrors(ConstraintViolationList $violationList, array $input = null): bool;

    public function toDni(string $dni): ?DNI
    {
        if (empty($dni)) {
            return null;
        }

        $violations = DNI::validate($dni);

        if ($this->watchForWarnings($violations)) {
            return null;
        }

        return DNI::make($dni);
    }

    public function toPostalAddress(string $address, string $postalCode): ?PostalAddress
    {

        $data = [
            'address' => $address,
            'postalCode' => $this->toPostalCode($postalCode)
        ];

        $violations = PostalAddress::validate($data);

        if ($this->watchForWarnings($violations)) {
            return null;
        }

        return PostalAddress::make(...[
            $data['address'],
            $data['postalCode'],
        ]);
    }

    private function toPostalCode(string $postalCode): ?PostalCode
    {
        $violations = PostalCode::validate($postalCode);

        if ($this->hasViolations($violations)) {
            return null;
        }
        return PostalCode::make($postalCode);
    }

    /**
     * @param string $situacion
     * @return null
     */
    protected function toJobStatus(string $situacion): ?JobStatus
    {
        $situacion = strtoupper($situacion);

        $status = null;
        switch ($situacion) {
            case 'ESTUDIANTE':
                $status = JobStatus::STUDENT();
                break;
            case 'EMPLEADO':
            case 'E':
            case 'EMPLEADA':
                $status = JobStatus::EMPLOYED();
                break;
            case 'DESEMPLEADO':
            case 'DESEMPLEADA':
                $status = JobStatus::UNEMPLOYED();
                break;
            case 'JUBILADO':
                $status = JobStatus::RETIRED();
                break;
            case 'NADA':
                $status = JobStatus::NOTHING();
                break;
        }
        return $status;
    }

    /**
     * @param string $phones
     * @return array
     */
    protected function toPhoneNumbers(string ...$phones): array
    {
        $phoneNumbers = [];

        $allTheNumbers = implode("/", $phones);
        $items = preg_split('/[\/|\-]/', $allTheNumbers);

        foreach ($items as $item) {
            $matches = [];

            if (empty($item)) {
                continue;
            }

            if (false !== strpos('@', $item)) {
                continue;
            }

            if (preg_match('/([ \p{L}]+)(\d{9})/u', $item, $matches)) {
                $item = sprintf('%s %s', $matches[2], $matches[1]);
            }

            $matches = [];
            if (preg_match_all('/(\d{9})([ \p{L}]*)/u', $item, $matches)) {

                $total = count($matches[0]);
                for ($i = 0; $i < $total; $i++) {
                    $phoneNumbers[] = PhoneNumber::make($matches[1][$i], $matches[2][$i]);
                }
            }
        }
        return $phoneNumbers;
    }

    public function toEmail(string $email): ?Email
    {

        if (strpos($email, 'NO TIENE') !== false) {
            $email = '';
        }

        if (strpos($email, 'NO USA') !== false) {
            $email = '';
        }

        if (empty($email)) {
            return null;
        }

        $violations = Email::validate($email);

        if ($this->watchForWarnings($violations)) {
            return null;
        }

        return Email::make($email);
    }
}
