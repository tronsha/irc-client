<?php

declare(strict_types=1);

namespace App\Service;

class CronHandlerService
{
    /**
     * @var CronService
     */
    private $cronService;

    /**
     * @var
     */
    private $cronJobs = [];

    /**
     * CronHandlerService constructor.
     * @param CronService $cronService
     */
    public function __construct(CronService $cronService)
    {
        $this->cronService = $cronService;
    }

    /**
     * @param $minute
     * @param $hour
     * @param $dayOfMonth
     * @param $month
     * @param $dayOfWeek
     *
     * @throws \Exception
     */
    public function run($minute, $hour, $dayOfMonth, $month, $dayOfWeek)
    {
        foreach ($this->cronJobs as $cron) {
            if (true === $this->cronService->compare($cron['cron'], $minute, $hour, $dayOfMonth, $month, $dayOfWeek)) {
                $cron['object']->{$cron['method']}($cron['param']);
            }
        }
    }
}
