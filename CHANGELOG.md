# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.1.1 - 2026-09-07

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- CI: declare `config.allow-plugins` for the coding-standard tooling so `composer` no longer aborts.

## 1.1.0 - 2026-09-07

### Added

- Tested against PHP 7.3 up to PHP 8.5 on a CI matrix.
- Test suite runs on PHPUnit 9.6, 10.5 and 11.5, selected automatically per PHP version.

### Deprecated

- `Exception\ValueError` is now `@internal`. Do not catch it by type - it will be replaced by the native `\ValueError` once the package requires PHP >= 8.0.

### Removed

- Unused `Exception\ValueError::fromError()` and `Exception\Runtime::fromThrowable()` named constructors; both classes keep their inherited constructor.

### Fixed

- `DomLoader::loadFile()` now throws `Exception\Runtime` for a missing or non-regular file path, as documented, instead of `Exception\LibXml`.
- `DomLoader::loadFile()` error handling on PHP 7.3 and 7.4, where `set_error_handler()` could not call the private handler.
- `DomLoader::loadFile()` reports a read failure as `Exception\Runtime` regardless of the caller's `error_reporting()` value.
- libxml error buffer is cleared before and after loading, so recovered parser warnings no longer leak into the global libxml state.
- `TypeError` when libxml signals a failure without recording an error.

## 1.0.1 - 2021-11-09

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- DomLoaderTest - namespace typo

## 1.0.0 - 2021-11-08

First stable release and first release as `dom-loader`.

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
