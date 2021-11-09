<?php

declare(strict_types=1);

namespace VaclavVanik\DomLoader\Exception;

use LibXMLError;
use RuntimeException;

use function sprintf;
use function trim;

final class LibXml extends RuntimeException implements Exception
{
    /** @var LibXMLError */
    private $libXmlError;

    private function __construct(LibXMLError $libXmlError, string $message)
    {
        $this->libXmlError = $libXmlError;

        parent::__construct($message);
    }

    public static function fromLibXMLError(LibXMLError $error): self
    {
        $toErrorMessage = static function (LibXMLError $error): string {
            $format = '%s on line: %d, column: %d';

            return sprintf($format, trim($error->message), $error->line, $error->column);
        };

        return new self($error, $toErrorMessage($error));
    }

    public function getLibXmlError(): LibXMLError
    {
        return $this->libXmlError;
    }
}
