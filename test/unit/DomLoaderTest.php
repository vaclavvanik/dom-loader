<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomLoader;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomLoader\DomLoader;
use VaclavVanik\DomLoader\Exception\LibXml;
use VaclavVanik\DomLoader\Exception\Runtime;
use VaclavVanik\DomLoader\Exception\ValueError;

final class DomLoaderTest extends TestCase
{
    /** @return iterable<string, array{string, int, (DOMDocument|null), string}> */
    public function provideLoadString(): iterable
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
        $this->expectErrorMessage('Argument #1 ($source) must not be empty');

        DomLoader::loadString('');
    }

    public function testLoadStringInvalidXml(): void
    {
        $this->expectException(LibXml::class);
        $this->expectErrorMessage('Extra content at the end of the document on line: 1, column: 2');

        DomLoader::loadString('<>');
    }

    /** @return iterable<string, array{string, int, (DOMDocument|null), string}> */
    public function provideLoadFile(): iterable
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
        $this->expectErrorMessage('Argument #1 ($filename) must not be empty');

        DomLoader::loadFile('');
    }

    public function testLoadFileContainsInvalidXml(): void
    {
        $this->expectException(LibXml::class);
        $this->expectErrorMessage('Extra content at the end of the document on line: 1, column: 2');

        DomLoader::loadFile(__DIR__ . '/_files/invalid.xml');
    }

    public function testLoadFileEmptyContent(): void
    {
        $this->expectException(LibXml::class);
        $this->expectErrorMessage('Document is empty on line: 1, column: 1');

        DomLoader::loadFile(__DIR__ . '/_files/empty.xml');
    }

    public function testLoadFileNotFile(): void
    {
        $this->expectException(Runtime::class);
        $this->expectErrorMessageMatches('/DOMDocument::load/');

        DomLoader::loadFile('.');
    }
}
