<?php

declare(strict_types=1);

namespace VaclavVanik\DomLoader\Exception;

use Error;

/**
 * Thrown when an argument is empty - a programming error, not a runtime condition.
 *
 * @internal Do not catch this type. Once the package requires PHP >= 8.0 it is replaced by the
 *           native \ValueError, which does not implement {@see Exception}.
 */
final class ValueError extends Error implements Exception
{
}
