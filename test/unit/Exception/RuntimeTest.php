<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomLoader\Exception\Runtime;

final class RuntimeTest extends TestCase
{
    public function testFromThrowable(): void
    {
        $throwable = new Exception('Error message');

        $runtime = Runtime::fromThrowable($throwable);

        $this->assertSame($throwable->getMessage(), $runtime->getMessage());
        $this->assertSame($throwable->getCode(), $runtime->getCode());
        $this->assertSame($throwable, $runtime->getPrevious());
    }
}
