<?php

declare(strict_types=1);

namespace App\Service\Cron;

class CheckService
{
    /**
     * @param string $cronString
     * @param \DateTime $time
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function compare(string $cronString, \DateTime $time): bool
    {
        $cronString = trim($cronString);
        $cronArray = explode(' ', $cronString);
        if (5 !== count($cronArray)) {
            throw new Exception('a cron has an error');
        }
        list($cronMinute, $cronHour, $cronDayOfMonth, $cronMonth, $cronDayOfWeek) = $cronArray;

        $cronDayOfWeek = $this->dowNameToNumber($cronDayOfWeek);
        $cronMonth = $this->monthNameToNumber($cronMonth);
        $cronDayOfWeek = (7 === (int) $cronDayOfWeek ? 0 : $cronDayOfWeek);

        $cronMinute = $this->getCronMinute($cronString);
        $cronHour = $this->getCronHour($cronString);
        $cronDayOfMonth = ('*' !== $cronDayOfMonth ? $this->prepare((string) $cronDayOfMonth, 1, 31) : $cronDayOfMonth);
        $cronMonth = ('*' !== $cronMonth ? $this->prepare((string) $cronMonth, 1, 12) : $cronMonth);
        $cronDayOfWeek = ('*' !== $cronDayOfWeek ? $this->prepare((string) $cronDayOfWeek, 0, 6) : $cronDayOfWeek);

        if (
            (
                '*' === $cronMinute || true === in_array($this->getMinute($time), $cronMinute, true)
            ) && (
                '*' === $cronHour || true === in_array($this->getHour($time), $cronHour, true)
            ) && (
                '*' === $cronMonth || true === in_array($this->getMonth($time), $cronMonth, true)
            ) && (
                (
                    (
                        '*' === $cronDayOfMonth || true === in_array($this->getDayOfMonth($time), $cronDayOfMonth, true)
                    ) && (
                        '*' === $cronDayOfWeek || true === in_array($this->getDayOfWeek($time), $cronDayOfWeek, true)
                    )
                ) || (
                    (
                        '*' !== $cronDayOfMonth
                    ) && (
                        '*' !== $cronDayOfWeek
                    ) && (
                        (
                            true === in_array($this->getDayOfMonth($time), $cronDayOfMonth, true)
                        ) || (
                            true === in_array($this->getDayOfWeek($time), $cronDayOfWeek, true)
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
     * @param \DateTime $time
     * @return int
     */
    private function getMinute(\DateTime $time): int
    {
        return (int) $time->format('i');
    }

    /**
     * @param \DateTime $time
     * @return int
     */
    private function getHour(\DateTime $time): int
    {
        return (int) $time->format('G');
    }

    /**
     * @param \DateTime $time
     * @return int
     */
    private function getMonth(\DateTime $time): int
    {
        return (int) $time->format('n');
    }

    /**
     * @param \DateTime $time
     * @return int
     */
    private function getDayOfMonth(\DateTime $time): int
    {
        return (int) $time->format('j');
    }

    /**
     * @param \DateTime $time
     * @return int
     */
    private function getDayOfWeek(\DateTime $time): int
    {
        return (int) $time->format('w');
    }

    /**
     * @param string $cronString
     * @return array
     */
    private function explodeCronString(string $cronString): array
    {
        return explode(' ', trim($cronString));
    }

    /**
     * @param string $cronString
     * @return array|string
     */
    private function getCronMinute(string $cronString)
    {
        $cronMinute = $this->explodeCronString($cronString)[0];
        if ('*' !== $cronMinute) {
            return '*';
        }

        return $this->prepare((string) $cronMinute, 0, 59);
    }

    /**
     * @param string $cronString
     * @return array|string
     */
    private function getCronHour(string $cronString)
    {
        $cronHour  = $this->explodeCronString($cronString)[1];
        if ('*' !== $cronHour) {
            return '*';
        }

        return $this->prepare((string) $cronHour, 0, 23);
    }

    /**
     * @param string $string
     * @param int    $a
     * @param int    $b
     *
     * @return array
     */
    private function prepare(string $string, int $a, int $b): array
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
    private function monthNameToNumber(string $subject): string
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
    private function dowNameToNumber(string $subject): string
    {
        $subject = strtolower($subject);
        $search = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        $replace = [0, 1, 2, 3, 4, 5, 6];

        return str_replace($search, $replace, $subject);
    }
}
