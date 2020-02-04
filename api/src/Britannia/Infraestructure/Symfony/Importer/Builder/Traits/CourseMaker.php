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


use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\VO\Course\TimeTable\DayOfWeek;
use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Britannia\Domain\VO\LessonLength;
use Carbon\CarbonImmutable;
use Symfony\Component\Validator\ConstraintViolationList;

trait CourseMaker
{

    private $patternA = '/((LUNES|MARTES|MIERCOLES|JUEVES|VIERNES)(.*)(LUNES|MARTES|MIERCOLES|JUEVES|VIERNES)  (.*))/U';

    /**
     * @param string $value
     * @return array
     */
    public function toLessons(string $value, ClassRoomId $classRoomId): array
    {
        //  $value = 'L-X-V 12:30 - 13:30J 20:15 - 21:15 SPEAKING';

        //  $value = 'L-M-X-J-V7:00 - 8:30';

        $split = "/(\d{1,2}:\d{2}\s*[\s|\-|A]\s*\d{1,2}:\d{2})/";

        $value = strip_tags($value);
        $value = trim($value, " \r\n");
        $value = str_replace('&nbsp;', '', $value);
        $value = mb_convert_case($value, MB_CASE_UPPER);
        $value = str_replace(['Á', 'É', 'Í', 'Ó', 'Ú'], ['A', 'E', 'I', 'O', 'O',], $value);
        $value = str_replace(['(', ')'], '', $value);

        $pieces = preg_split($split, $value, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $pieces = array_filter($pieces, function ($value) {
            return !empty(trim($value));
        });

        $total = count($pieces);

        $temp = [];
        for ($index = 0; $index < $total - 1; $index = $index + 2) {
            $next = $index + 1;
            $temp[] = $this->toLesson($pieces[$index], $pieces[$next], $classRoomId);
        }

        $lessons = array_merge([], ...$temp);

        return array_filter($lessons);
    }

    private function toLesson(string $days, string $hours, ClassRoomId $classRoomId)
    {

        $interval = $this->toInterval($hours);
        list($start, $end) = $interval;

        $days = $this->toDays($days);


        $temp = [];
        foreach ($days as $day) {
            $temp[] = TimeSheet::make($day, $start, $end, $classRoomId);
        }

        return $temp;
    }

    private function toInterval(string $hours): ?array
    {
        $pattern = '/(\d{1,2}:\d{2})\s*[\s|\-|A]\s*(\d{1,2}:\d{2})/';

        $matches = [];

        if (!preg_match($pattern, $hours, $matches)) {
            return null;
        }

        $start = CarbonImmutable::make($matches[1]);
        $end = CarbonImmutable::make($matches[2]);

        return [
            $start,
            $end
        ];
    }

    /**
     * @param string $days
     * @return DayOfWeek[]
     */
    private function toDays(string $days): array
    {
        $days = str_replace(['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'], ['L', 'M', 'X', 'J', 'V'], $days);
        $days = str_replace([',', 'Y', ' -', '- ', '-', 'DE'], [''], $days);
        $days = trim($days);

        $days = $this->fixDaysInterval($days);

        if (!preg_match('/^(L|M|X|J|V|\s){1,}$/', $days)) {
            return [];
        }

        $letters = preg_split('//', $days, -1, PREG_SPLIT_NO_EMPTY);

        $temp = [];
        foreach ($letters as $letter) {
            switch ($letter) {
                case 'L':
                    $temp[] = DayOfWeek::MONDAY();
                    break;
                case 'M':
                    $temp[] = DayOfWeek::TUESDAY();
                    break;
                case 'X':
                    $temp[] = DayOfWeek::WEDNESDAY();
                    break;
                case 'J':
                    $temp[] = DayOfWeek::THURSDAY();
                    break;
                case 'V':
                    $temp[] = DayOfWeek::FRIDAY();
                    break;
            }
        }

        return $temp;
    }

    private function fixDaysInterval(string $days): string
    {

        $mapped = [
            'L' => 0,
            'M' => 1,
            'X' => 2,
            'J' => 3,
            'V' => 4,
        ];

        $inversed = array_flip($mapped);

        $matches = [];
        if (!preg_match('/(L|M|X|J|V)\s*A\s*(L|M|X|J|V)/', $days, $matches)) {
            return $days;
        }


        $first = $mapped[$matches[1]];
        $last = $mapped[$matches[2]];

        $temp = '';
        for ($i = $first; $i <= $last; $i++) {
            $temp .= $inversed[$i];
        }

        return $temp;
    }

    abstract protected function watchForErrors(ConstraintViolationList $violationList, array $input = null): bool;

    abstract protected function watchForWarnings(ConstraintViolationList $violationList, array $input = null): bool;

    abstract protected function hasViolations(ConstraintViolationList $violationList): bool;

    abstract protected function findOneOrCreate(object $entity, array $criteria): ?object;

    abstract protected function findOneOrNull(string $className, array $criteria): ?object;
}
