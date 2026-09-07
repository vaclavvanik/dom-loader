<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader\Exception;

use Error;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomLoader\Exception\Exception;
use VaclavVanik\DomLoader\Exception\ValueError;

final class ValueErrorTest extends TestCase
{
    public function testIsErrorAndPackageException(): void
    {
        $exception = new ValueError('message');

        $this->assertInstanceOf(Error::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
