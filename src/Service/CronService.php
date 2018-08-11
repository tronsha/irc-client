<?php

declare(strict_types=1);

namespace App\Service;

class CronService
{
    /**
     * @var
     */
    private $cronJobs = [];

    /**
     * @param $minute
     * @param $hour
     * @param $dayOfMonth
     * @param $month
     * @param $dayOfWeek
     * @throws \Exception
     */
    public function run($minute, $hour, $dayOfMonth, $month, $dayOfWeek)
    {
        foreach ($this->cronJobs as $cron) {
            if (true === $this->compare($cron['cron'], $minute, $hour, $dayOfMonth, $month, $dayOfWeek)) {
                $cron['object']->{$cron['method']}($cron['param']);
            }
        }
    }

    /**
     * @param string $cronString
     * @param int $minute
     * @param int $hour
     * @param int $dayOfMonth
     * @param int $month
     * @param int $dayOfWeek
     * @throws \Exception
     * @return bool
     */
    public function compare($cronString, $minute, $hour, $dayOfMonth, $month, $dayOfWeek)
    {
        $cronString = trim($cronString);
        $cronArray = explode(' ', $cronString);
        if (5 !== count($cronArray)) {
            throw new Exception('a cron has an error');
        }
        list($cronMinute, $cronHour, $cronDayOfMonth, $cronMonth, $cronDayOfWeek) = $cronArray;
        $cronDayOfWeek = $this->dowNameToNumber($cronDayOfWeek);
        $cronMonth = $this->monthNameToNumber($cronMonth);
        $cronDayOfWeek = (7 === intval($cronDayOfWeek) ? 0 : $cronDayOfWeek);
        $cronMinute = ('*' !== $cronMinute ? $this->prepare($cronMinute, 0, 59) : $cronMinute);
        $cronHour = ('*' !== $cronHour ? $this->prepare($cronHour, 0, 23) : $cronHour);
        $cronDayOfMonth = ('*' !== $cronDayOfMonth ? $this->prepare($cronDayOfMonth, 1, 31) : $cronDayOfMonth);
        $cronMonth = ('*' !== $cronMonth ? $this->prepare($cronMonth, 1, 12) : $cronMonth);
        $cronDayOfWeek = ('*' !== $cronDayOfWeek ? $this->prepare($cronDayOfWeek, 0, 6) : $cronDayOfWeek);
        if (
            (
                '*' === $cronMinute  || true === in_array($minute, $cronMinute, true)
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
     * @param int $a
     * @param int $b
     * @return array
     */
    public function prepare($string, $a, $b)
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
                $min = intval($min);
                $max = intval($max);
                for ($i = $min, $j = 0; $i <= $max; $i++, $j++) {
                    if (0 === ($j % $steps)) {
                        $array[] = $i;
                    }
                }
            } else {
                $array[] = intval($value);
            }
        }
        return $array;
    }

    /**
     * @param string $subject
     * @return string
     */
    public function monthNameToNumber($subject)
    {
        $subject = strtolower($subject);
        $search = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $replace = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        return str_replace($search, $replace, $subject);
    }

    /**
     * @param string $subject
     * @return string
     */
    public function dowNameToNumber($subject)
    {
        $subject = strtolower($subject);
        $search = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        $replace = [0, 1, 2, 3, 4, 5, 6];
        return str_replace($search, $replace, $subject);
    }
}
