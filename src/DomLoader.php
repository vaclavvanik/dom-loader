<?php

declare(strict_types=1);

namespace VaclavVanik\DomLoader;

use DOMDocument;
use LibXMLError;

use function is_file;
use function libxml_clear_errors;
use function libxml_get_last_error;
use function libxml_use_internal_errors;
use function restore_error_handler;
use function set_error_handler;
use function sprintf;
use function strpos;

use const LIBXML_ERR_FATAL;

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

        libxml_clear_errors();
        $previousInternalErrors = libxml_use_internal_errors(true);

        try {
            self::assertLoadResult($doc->loadXML($source, $options));
        } finally {
            libxml_use_internal_errors($previousInternalErrors);
            libxml_clear_errors();
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

        // Local paths only; leave stream wrappers (http://, php://, …) for DOMDocument to resolve.
        // BC shim for PHP < 8.0: use str_contains($filename, '://') once the minimum is >= 8.0.
        if (strpos($filename, '://') === false && ! is_file($filename)) {
            throw new Exception\Runtime(sprintf('File "%s" does not exist or is not a regular file', $filename));
        }

        $doc = self::createDoc($doc);

        libxml_clear_errors();
        $previousInternalErrors = libxml_use_internal_errors(true);

        // Capture the file-access diagnostic (failed to open stream, read error, open_basedir …)
        // instead of throwing from the handler, so a benign notice on an otherwise successful
        // load cannot turn into an exception.
        $readError = '';

        try {
            set_error_handler(static function (int $no, string $message) use (&$readError): bool {
                if ($readError === '') {
                    $readError = $message;
                }

                return true;
            });

            if ($doc->load($filename, $options) === false) {
                if ($readError !== '') {
                    throw new Exception\Runtime($readError);
                }

                self::throwException();
            }
        } finally {
            libxml_use_internal_errors($previousInternalErrors);
            libxml_clear_errors();
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

        if ($libXmlError === false) {
            // libxml signalled failure without recording an error - should not happen in practice.
            $libXmlError = new LibXMLError();
            $libXmlError->level = LIBXML_ERR_FATAL;
            $libXmlError->code = 0;
            $libXmlError->column = 0;
            $libXmlError->message = 'Unknown libxml error';
            $libXmlError->file = '';
            $libXmlError->line = 0;
        }

        throw Exception\LibXml::fromLibXMLError($libXmlError);
    }
}
