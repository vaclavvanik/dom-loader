<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader\Exception;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use VaclavVanik\DomLoader\Exception\Exception;
use VaclavVanik\DomLoader\Exception\Runtime;

final class RuntimeTest extends TestCase
{
    public function testIsRuntimeExceptionAndPackageException(): void
    {
        $exception = new Runtime('message');

        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
