<?php

declare(strict_types=1);

namespace VaclavVanik\DomLoader\Exception;

use Error;
use Throwable;

final class ValueError extends Error implements Exception
{
    public static function fromError(Throwable $error): self
    {
        return new self($error->getMessage(), $error->getCode(), $error);
    }
}
