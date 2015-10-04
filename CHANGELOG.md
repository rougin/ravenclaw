# Changelog

All Notable changes to `Describe` will be documented in this file

## [1.1.3](https://github.com/rougin/transcribe/compare/v1.1.2...v1.1.3) - 2015-07-26

### Fixed
- Bug in selecting constraints in a `MySQL` database driver

## [1.1.2](https://github.com/rougin/transcribe/compare/v1.1.1...v1.1.2) - 2015-07-03

### Changed
- Renamed `get_foreign_*` to `get_referenced_*`
- `MySQL` database driver logic

### Fixed
- Missing `setForeign()` method in `Column` class

## [1.1.1](https://github.com/rougin/transcribe/compare/v1.1.0...v1.1.1) - 2015-06-26

### Changed
- Rewritten database drivers from scratch

## [1.1.0](https://github.com/rougin/transcribe/compare/v1.0.0...v1.1.0) - 2015-05-30

### Added
- Support for foreign keys referenced in other databases
- `showTables()` method in `Describe` class

### Changed
- Namespace to 'Rougin\Describe'

## 1.0.0 - 2015-04-01

### Added
- `Describe` library