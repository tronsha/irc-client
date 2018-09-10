<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Service\Cron\CheckService as CronService;

class CronServiceTest extends \PHPUnit\Framework\TestCase
{
    private $cron;

    protected function setUp()
    {
        $this->cron = new CronService();
    }

    protected function tearDown()
    {
        unset($this->cron);
    }

    public function invokeMethod(&$object, string $methodName, ...$parameters)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param bool      $expected
     * @param string    $cronString
     * @param \DateTime $time
     *
     * @dataProvider compareProvider
     */
    public function testCompare($expected, $cronString, $time)
    {
        $this->assertSame(
            $expected,
            $this->invokeMethod(
                $this->cron,
                'compare',
                $cronString,
                $time
            )
        );
    }

    public function compareProvider()
    {
        return [
//            [true, '* * * * *', 15, 12, 1, 1, 0],
//            [true, '15 * * * *', 15, 12, 1, 1, 0],
//            [true, '* 12 * * *', 15, 12, 1, 1, 0],
//            [true, '* * 1 * *', 15, 12, 1, 1, 0],
//            [true, '* * * 1 *', 15, 12, 1, 1, 0],
            [true, '* * * * *', new \DateTime('2018-01-01 12:15:00')],
            [true, '15 * * * *', new \DateTime('2018-01-01 12:15:00')],
            [true, '* 12 * * *', new \DateTime('2018-01-01 12:15:00')],
            [true, '* * 1 * *', new \DateTime('2018-01-01 12:15:00')],
            [true, '* * * 1 *', new \DateTime('2018-01-01 12:15:00')],
//            [true, '* * * * 0', 15, 12, 1, 1, 0],
//            [true, '* * * * 7', 15, 12, 1, 1, 0],
            [true, '* * * * 0', new \DateTime('2018-01-07 12:15:00')],
            [true, '* * * * 7', new \DateTime('2018-01-07 12:15:00')],
//            [true, '15 12 * * *', 15, 12, 1, 1, 0],
            [true, '15 12 * * *', new \DateTime('2018-01-01 12:15:00')],
//            [false, '30 * * * *', 15, 12, 1, 1, 0],
            [false, '30 * * * *', new \DateTime('2018-01-01 12:15:00')],
//            [false, '* 13 * * *', 15, 12, 1, 1, 0],
            [false, '* 13 * * *', new \DateTime('2018-01-01 12:15:00')],
//            [false, '* * 2 * *', 15, 12, 1, 1, 0],
            [false, '* * 2 * *', new \DateTime('2018-01-01 12:15:00')],
//            [false, '* * * 2 *', 15, 12, 1, 1, 0],
            [false, '* * * 2 *', new \DateTime('2018-01-01 12:15:00')],
//            [false, '* * * * 1', 15, 12, 1, 1, 0],
            [false, '* * * * 1', new \DateTime('2018-01-07 12:15:00')],
//            [false, '15 13 * * *', 15, 12, 1, 1, 0],
            [false, '15 13 * * *', new \DateTime('2018-01-01 12:15:00')],
//            [false, '30 12 * * *', 15, 12, 1, 1, 0],
//            [true, '* * 1 * 1', 15, 12, 1, 1, 0],
//            [true, '* * 2 * 0', 15, 12, 1, 1, 0],
//            [false, '* * 2 * 1', 15, 12, 1, 1, 0],
//            [false, '* * 1 2 1', 15, 12, 1, 1, 0],
//            [false, '* * 2 2 0', 15, 12, 1, 1, 0],
//            [true, '0-29 * * * *', 15, 12, 1, 1, 0],
//            [false, '30-59 * * * *', 15, 12, 1, 1, 0],
//            [true, '0,15,30,45 * * * *', 15, 12, 1, 1, 0],
//            [false, '0,20,40 * * * *', 15, 12, 1, 1, 0],
//            [true, '10-20,30-40 * * * *', 15, 12, 1, 1, 0],
//            [false, '20-30,40-50 * * * *', 15, 12, 1, 1, 0],
//            [true, '*/5 * * * *', 15, 12, 1, 1, 0],
//            [false, '*/10 * * * *', 15, 12, 1, 1, 0],
//            [true, '0-30/5 * * * *', 15, 12, 1, 1, 0],
//            [false, '30-55/5 * * * *', 15, 12, 1, 1, 0],
//            [true, '2-32/5 * * * *', 17, 12, 1, 1, 0],
//            [false, '2-32/5 * * * *', 15, 12, 1, 1, 0],
//            [true, '1-20/3 * * * *', 1, 12, 1, 1, 0],
//            [true, '1-20/3 * * * *', 4, 12, 1, 1, 0],
//            [false, '1-20/3 * * * *', 3, 12, 1, 1, 0],
//            [true, '0 */6 * * *', 0, 0, 1, 1, 0],
//            [true, '0 */6 * * *', 0, 6, 1, 1, 0],
//            [true, '0 */6 * * *', 0, 12, 1, 1, 0],
//            [true, '0 */6 * * *', 0, 18, 1, 1, 0],
//            [false, '0 */6 * * *', 0, 14, 1, 1, 0],
//            [true, '0 12 * * SUN', 0, 12, 1, 1, 0],
//            [false, '0 12 * * SAT', 0, 12, 1, 1, 0],
//            [true, '0 12 * JAN-JUN *', 0, 12, 1, 1, 0],
//            [false, '0 12 * JUL-DEC *', 0, 12, 1, 1, 0],
        ];
    }
}
