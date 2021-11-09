# Dom-loader

This package provides a safety way to load string or file to [DOMDocument](https://www.php.net/manual/en/class.domdocument.php).

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

and finally loading to custom DOMDocument is supported:

```php
<?php

declare(strict_types=1);

use DOMDocument;
use VaclavVanik\DomLoader;

$dom = DomLoader\DomLoader::loadFile($file, 0, new DOMDocument('1.0', 'utf-8'));
// or
$dom = DomLoader\DomLoader::loadString($string, 0, new DOMDocument('1.0', 'utf-8'));
```

## Run check - coding standards and php-unit

```bash
make check
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
