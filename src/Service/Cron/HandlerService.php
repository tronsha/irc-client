<?php

declare(strict_types=1);

namespace App\Service\Cron;

class HandlerService
{
    /**
     * @var CheckService
     */
    private $cronCheckService;

    /**
     * @var
     */
    private $cronJobs = [];

    /**
     * CronHandlerService constructor.
     * @param CheckService $cronCheckService
     */
    public function __construct(CheckService $cronCheckService)
    {
        $this->cronCheckService = $cronCheckService;
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
            if (true === $this->cronCheckService->compare($cron['cron'], $minute, $hour, $dayOfMonth, $month, $dayOfWeek)) {
                $cron['object']->{$cron['method']}($cron['param']);
            }
        }
    }
}
