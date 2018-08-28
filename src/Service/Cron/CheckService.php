<?php

declare(strict_types=1);

namespace App\Service\Cron;

class CheckService
{
    /**
     * @param string $cronString
     * @param \DateTime $dateTime
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function compare(string $cronString, \DateTime $dateTime): bool
    {
        $minute = (int) $dateTime->format('i');
        $hour = (int) $dateTime->format('G');
        $month = (int) $dateTime->format('n');
        $dayOfMonth = (int) $dateTime->format('j');
        $dayOfWeek = (int) $dateTime->format('w');

        $cronString = trim($cronString);
        $cronArray = explode(' ', $cronString);
        if (5 !== count($cronArray)) {
            throw new Exception('a cron has an error');
        }
        list($cronMinute, $cronHour, $cronDayOfMonth, $cronMonth, $cronDayOfWeek) = $cronArray;

        $cronDayOfWeek = $this->dowNameToNumber($cronDayOfWeek);
        $cronMonth = $this->monthNameToNumber($cronMonth);
        $cronDayOfWeek = (7 === (int) $cronDayOfWeek ? 0 : $cronDayOfWeek);

        $cronMinute = ('*' !== $cronMinute ? $this->prepare((string) $cronMinute, 0, 59) : $cronMinute);
        $cronHour = ('*' !== $cronHour ? $this->prepare((string) $cronHour, 0, 23) : $cronHour);
        $cronDayOfMonth = ('*' !== $cronDayOfMonth ? $this->prepare((string) $cronDayOfMonth, 1, 31) : $cronDayOfMonth);
        $cronMonth = ('*' !== $cronMonth ? $this->prepare((string) $cronMonth, 1, 12) : $cronMonth);
        $cronDayOfWeek = ('*' !== $cronDayOfWeek ? $this->prepare((string) $cronDayOfWeek, 0, 6) : $cronDayOfWeek);

        if (
            (
                '*' === $cronMinute || true === in_array($minute, $cronMinute, true)
            ) && (
                '*' === $cronHour || true === in_array($hour, $cronHour, true)
            ) && (
                '*' === $cronMonth || true === in_array($month, $cronMonth, true)
            ) && (
                (
                    (
                        '*' === $cronDayOfMonth || true === in_array($dayOfMonth, $cronDayOfMonth, true)
                    ) && (
                        '*' === $cronDayOfWeek || true === in_array($dayOfWeek, $cronDayOfWeek, true)
                    )
                ) || (
                    (
                        '*' !== $cronDayOfMonth
                    ) && (
                        '*' !== $cronDayOfWeek
                    ) && (
                        (
                            true === in_array($dayOfMonth, $cronDayOfMonth, true)
                        ) || (
                            true === in_array($dayOfWeek, $cronDayOfWeek, true)
                        )
                    )
                )
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $string
     * @param int    $a
     * @param int    $b
     *
     * @return array
     */
    public function prepare(string $string, int $a, int $b): array
    {
        $values = [];
        if (false !== strpos($string, ',')) {
            $values = explode(',', $string);
        } else {
            $values[] = $string;
        }
        $array = [];
        foreach ($values as $value) {
            $steps = 1;
            if (false !== strpos($string, '/')) {
                list($value, $steps) = explode('/', $string);
            }
            if ('*' === $value) {
                $value = $a . '-' . $b;
            }
            if (false !== strpos($value, '-')) {
                list($min, $max) = explode('-', $value);
                $min = (int) $min;
                $max = (int) $max;
                for ($i = $min, $j = 0; $i <= $max; $i++, $j++) {
                    if (0 === ($j % $steps)) {
                        $array[] = $i;
                    }
                }
            } else {
                $array[] = (int) $value;
            }
        }

        return $array;
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    public function monthNameToNumber(string $subject): string
    {
        $subject = strtolower($subject);
        $search = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $replace = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        return str_replace($search, $replace, $subject);
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    public function dowNameToNumber(string $subject): string
    {
        $subject = strtolower($subject);
        $search = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        $replace = [0, 1, 2, 3, 4, 5, 6];

        return str_replace($search, $replace, $subject);
    }
}
