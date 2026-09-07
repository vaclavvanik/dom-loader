<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader;

use DOMDocument;
use PHPUnit\Framework\Attributes\DataProvider as DataProviderAttribute;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomLoader\DomLoader;
use VaclavVanik\DomLoader\Exception\LibXml;
use VaclavVanik\DomLoader\Exception\Runtime;
use VaclavVanik\DomLoader\Exception\ValueError;

final class DomLoaderTest extends TestCase
{
    // BC shim: the @dataProvider annotations are kept only for PHPUnit 9.6 (PHP < 8.1), which does
    // not read the #[DataProviderAttribute] attribute. Drop them once the minimum PHPUnit is >= 10.

    /** @return iterable<string, array{string, int, DOMDocument|null, string}> */
    public static function provideLoadString(): iterable
    {
        $xml = '<root/>';
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        yield 'without input doc' => [
            $xml,
            0,
            null,
            $doc->saveXML(),
        ];

        yield 'with input doc' => [
            $xml,
            0,
            $doc,
            $doc->saveXML(),
        ];
    }

    /** @dataProvider provideLoadString */
    #[DataProviderAttribute('provideLoadString')]
    public function testLoadString(string $string, int $options, ?DOMDocument $inDoc, string $xml): void
    {
        $doc = DomLoader::loadString($string, $options, $inDoc);

        $this->assertSame($xml, $doc->saveXML());

        if (! $inDoc) {
            return;
        }

        $this->assertSame($inDoc, $doc);
    }

    public function testLoadStringEmptyXml(): void
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($source) must not be empty');

        DomLoader::loadString('');
    }

    public function testLoadStringInvalidXml(): void
    {
        $this->expectException(LibXml::class);
        $this->expectExceptionMessage('Extra content at the end of the document on line: 1, column: 2');

        DomLoader::loadString('<>');
    }

    /** @return iterable<string, array{string, int, DOMDocument|null, string}> */
    public static function provideLoadFile(): iterable
    {
        $file = __DIR__ . '/_files/root.xml';
        $doc = new DOMDocument();
        $doc->load($file);

        yield 'without input doc' => [
            $file,
            0,
            null,
            $doc->saveXML(),
        ];

        yield 'with input doc' => [
            $file,
            0,
            $doc,
            $doc->saveXML(),
        ];
    }

    /** @dataProvider provideLoadFile */
    #[DataProviderAttribute('provideLoadFile')]
    public function testLoadFile(string $file, int $options, ?DOMDocument $inDoc, string $xml): void
    {
        $doc = DomLoader::loadFile($file, $options, $inDoc);

        $this->assertSame($xml, $doc->saveXML());

        if (! $inDoc) {
            return;
        }

        $this->assertSame($inDoc, $doc);
    }

    public function testLoadFileEmptyFile(): void
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($filename) must not be empty');

        DomLoader::loadFile('');
    }

    public function testLoadFileContainsInvalidXml(): void
    {
        $this->expectException(LibXml::class);
        $this->expectExceptionMessage('Extra content at the end of the document on line: 1, column: 2');

        DomLoader::loadFile(__DIR__ . '/_files/invalid.xml');
    }

    public function testLoadFileEmptyContent(): void
    {
        $this->expectException(LibXml::class);
        $this->expectExceptionMessage('Document is empty on line: 1, column: 1');

        DomLoader::loadFile(__DIR__ . '/_files/empty.xml');
    }

    public function testLoadFileNotFile(): void
    {
        $this->expectException(Runtime::class);
        $this->expectExceptionMessageMatches('/does not exist or is not a regular file/');

        DomLoader::loadFile('.');
    }

    public function testLoadFileDoesNotExist(): void
    {
        $this->expectException(Runtime::class);
        $this->expectExceptionMessageMatches('/does not exist or is not a regular file/');

        DomLoader::loadFile(__DIR__ . '/_files/does-not-exist.xml');
    }
}
