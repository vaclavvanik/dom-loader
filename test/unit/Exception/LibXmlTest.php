<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader\Exception;

use LibXMLError;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomLoader\Exception\LibXml;

final class LibXmlTest extends TestCase
{
    public function testFromLibXMLError(): void
    {
        $libXmlError = new LibXMLError();
        $libXmlError->message = 'Error message';
        $libXmlError->line = 1;
        $libXmlError->column = 1;
        $libXmlError->code = 0;

        $exception = LibXml::fromLibXMLError($libXmlError);

        $this->assertSame($libXmlError, $exception->getLibXmlError());
        $this->assertMatchesRegularExpression('/(.+) on line: (\d+), column: (\d+)/', $exception->getMessage());
    }
}
