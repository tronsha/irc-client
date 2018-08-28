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
     * @throws \Exception
     */
    public function run()
    {
        $now = new \DateTime('now');
        foreach ($this->cronJobs as $cron) {
            if (true === $this->cronCheckService->compare($cron['cron'], $now)) {
                $cron['object']->{$cron['method']}($cron['param']);
            }
        }
    }
}
