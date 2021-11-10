<?php

declare(strict_types=1);

namespace VaclavVanik\DomLoader;

use DOMDocument;
use ErrorException;

use function error_reporting;
use function libxml_clear_errors;
use function libxml_get_last_error;
use function libxml_use_internal_errors;
use function restore_error_handler;
use function set_error_handler;

abstract class DomLoader
{
    /**
     * @throws Exception\LibXml if xml string parsing failed.
     * @throws Exception\ValueError if source (xml string) is empty.
     */
    public static function loadString(string $source, int $options = 0, ?DOMDocument $doc = null): DOMDocument
    {
        if ($source === '') {
            throw new Exception\ValueError('Argument #1 ($source) must not be empty');
        }

        $doc = self::createDoc($doc);

        $previousInternalErrors = libxml_use_internal_errors(true);

        try {
            self::assertLoadResult($doc->loadXML($source, $options));
        } finally {
            libxml_use_internal_errors($previousInternalErrors);
        }

        return $doc;
    }

    /**
     * @throws Exception\LibXml if xml file parsing failed.
     * @throws Exception\Runtime if error occurs when reading file.
     * @throws Exception\ValueError if filename is empty.
     */
    public static function loadFile(string $filename, int $options = 0, ?DOMDocument $doc = null): DOMDocument
    {
        if ($filename === '') {
            throw new Exception\ValueError('Argument #1 ($filename) must not be empty');
        }

        $doc = self::createDoc($doc);

        $previousInternalErrors = libxml_use_internal_errors(true);

        try {
            $errorHandler = static function (int $no, string $str, string $file = '', int $line = 0): bool {
                if (! (error_reporting() & $no)) {
                    return false;
                }

                throw new ErrorException($str, 0, $no, $file, $line);
            };

            set_error_handler($errorHandler);

            self::assertLoadResult($doc->load($filename, $options));
        } catch (ErrorException $e) {
            throw Exception\Runtime::fromThrowable($e);
        } finally {
            libxml_use_internal_errors($previousInternalErrors);
            restore_error_handler();
        }

        return $doc;
    }

    private static function createDoc(?DOMDocument $doc): DOMDocument
    {
        if ($doc === null) {
            return new DOMDocument('1.0', 'utf-8');
        }

        return $doc;
    }

    /**
     * @param bool|DOMDocument $result
     *
     * @throws Exception\LibXml
     */
    private static function assertLoadResult(/* bool|DOMDocument */ $result): void
    {
        if ($result !== false) {
            return;
        }

        self::throwException();
    }

    /** @throws Exception\LibXml */
    private static function throwException(): void
    {
        $libXmlError = libxml_get_last_error();
        libxml_clear_errors();

        throw Exception\LibXml::fromLibXMLError($libXmlError);
    }
}
