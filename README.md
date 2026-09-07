# DomLoader

Load an XML string or file into a [DOMDocument](https://www.php.net/manual/en/class.domdocument.php)
and get back either a valid document or a typed exception — never a half-parsed document or a silent `false`.

## Why

`DOMDocument::loadXML()` and `DOMDocument::load()` return `false` on failure, push parser errors into the
global libxml error buffer, and emit PHP warnings when a file cannot be read. `DomLoader` wraps all of that:

- returns a ready-to-use `DOMDocument`, or throws
- tells a parser error (`Exception\LibXml`) apart from a file read error (`Exception\Runtime`)
- flips `libxml_use_internal_errors()` and clears the libxml error buffer for you, so nothing leaks into global state

No runtime dependencies beyond `ext-dom` and `ext-libxml`. Tested on PHP 7.3 - 8.5.

## Install

You can install this package via composer.

``` bash
composer require vaclavvanik/dom-loader
```

## Usage

```php
<?php

declare(strict_types=1);

use VaclavVanik\DomLoader;

$dom = DomLoader\DomLoader::loadFile($file);
// or
$dom = DomLoader\DomLoader::loadString($string);
```

passing dom load `$options` is also available:

```php
<?php

declare(strict_types=1);

use VaclavVanik\DomLoader;

use const LIBXML_PARSEHUGE;

$dom = DomLoader\DomLoader::loadFile($file, LIBXML_PARSEHUGE);
// or
$dom = DomLoader\DomLoader::loadString($string, LIBXML_PARSEHUGE);
```

and finally loading into custom DOMDocument is supported:

```php
<?php

declare(strict_types=1);

use DOMDocument;
use VaclavVanik\DomLoader;

$dom = DomLoader\DomLoader::loadFile($file, 0, new DOMDocument('1.0', 'utf-8'));
// or
$dom = DomLoader\DomLoader::loadString($string, 0, new DOMDocument('1.0', 'utf-8'));
```

## Exceptions

load methods throw:

- [Exception\LibXml](src/Exception/LibXml.php) if XML parsing failed.
- [Exception\Runtime](src/Exception/Runtime.php) if the file cannot be read (missing, not a regular file, unreadable).
- [Exception\ValueError](src/Exception/ValueError.php) if filename or xml string is empty.

`Exception\LibXml` and `Exception\Runtime` implement [Exception\Exception](src/Exception/Exception.php), so
`catch (VaclavVanik\DomLoader\Exception\Exception $e)` catches either.

`Exception\ValueError` signals an empty argument — a programming error to fix, not to catch. It is `@internal`
and will become the native `\ValueError` once the package requires PHP >= 8.0.

## Run check - coding standards and php-unit

Install dependencies:

```bash
make install
```

Run check:

```bash
make check
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
