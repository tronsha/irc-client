<?php

declare(strict_types=1);

namespace App\Test\Service;

class IrcServiceTest extends \PHPUnit\Framework\TestCase
{
    public function invokeMethod(&$object, string $methodName, ...$parameters)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
    
    public function test()
    {
        $this->assertTrue(true);
    }
}
