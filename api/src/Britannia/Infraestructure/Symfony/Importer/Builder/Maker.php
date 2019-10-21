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


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\School\School;
use Britannia\Domain\Entity\Student\Tutor;
use Britannia\Domain\VO\BankAccount;
use Britannia\Domain\VO\Job;
use Britannia\Domain\VO\JobStatus;
use Britannia\Domain\VO\NumOfYears;
use Britannia\Domain\VO\Payment;
use Britannia\Domain\VO\PaymentMode;
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

trait Maker
{

    abstract protected function watchForErrors(ConstraintViolationList $violationList, array $input = null): bool;

    abstract protected function watchForWarnings(ConstraintViolationList $violationList, array $input = null): bool;

    abstract protected function hasViolations(ConstraintViolationList $violationList): bool;

    abstract protected function findOne(object $entity, array $criteria): ?object;

    public function toFullName(array $input): ?FullName
    {
        $violations = FullName::validate($input);

        if ($this->watchForErrors($violations)) {
            return null;
        }

        return FullName::make(...[
            $input['firstName'],
            $input['lastName'],
        ]);
    }

    public function toDni(string $dni): ?DNI
    {
        if (empty($dni)) {
            return null;
        }

        $violations = DNI::validate($dni);

        if ($this->watchForErrors($violations)) {
            return null;
        }

        return DNI::make($dni);
    }

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

        if ($this->watchForErrors($violations, $input)) {
            return null;
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

    private function toIban(string $iban): ?Iban
    {
        $violations = Iban::validate($iban);

        if ($this->hasViolations($violations)) {
            return null;
        }

        return Iban::make($iban);
    }

    public function toDate(string $date): ?\DateTime
    {
        $validator = Validation::createValidator();

        $violations = $validator->validate($date, [
            new DateTime([
                'message' => "La fecha no es correcta"
            ])
        ]);

        if ($this->watchForErrors($violations)) {
            return null;
        }

        return new \DateTime($date);
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
        $academy = new Academy();
        $academy->setName($name);

        $academy = $this->findOne($academy, [
            'name' => $name
        ]);
        return $academy;
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
     * @param string $name
     * @return School|null
     */
    protected function toSchool(string $name): ?School
    {
        $school = new School();
        $school->setName($name);

        return $this->findOne($school, [
            'name' => $name
        ]);
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


    protected function toTutor(array $data): ?Tutor
    {
        $data['lastName'] = trim($data['lastName']);

        if (empty($data['lastName'])) {
            $pieces = explode(',', $data['firstName']);

            $data['lastName'] = array_pop($pieces);
            $data['firstName'] = implode(' ', $pieces);
        }

//        $data['firstName'] = str_replace(',', ' ', $data['firstName']);

        $fullName = $this->toFullName([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName']
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
        $emails[] = $this->toEmail($data['email']);

        if (false !== strpos((string)$data['extra'], '@')) {

            $emails[] = $this->toEmail($data['extra']);
        }

        dump($data['extra'], $emails);


        $emails = array_filter($emails);

        $tutor = new Tutor();
        $tutor->setFullName($fullName);
        $tutor->setDni($dni);
        $tutor->setAddress($address);
        $tutor->setJob($job);
        $tutor->setPhoneNumbers($phoneNumbers);
        $tutor->setEmails($emails);


        return $this->findOne($tutor, [

        ]);

    }
}
