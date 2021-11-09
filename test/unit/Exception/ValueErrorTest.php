<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader\Exception;

use Error;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomLoader\Exception\ValueError;

final class ValueErrorTest extends TestCase
{
    public function testFromThrowable(): void
    {
        $error = new Error('Error message');

        $valueError = ValueError::fromError($error);

        $this->assertSame($error->getMessage(), $valueError->getMessage());
        $this->assertSame($error->getCode(), $valueError->getCode());
        $this->assertSame($error, $valueError->getPrevious());
    }
}
