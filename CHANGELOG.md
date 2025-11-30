# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), 
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-11-30

### Changed
- Renamed `get_ini` to `ini_get` and `set_ini` to `ini_set`.
- Updated the `name` parameter in `copy` and `rename` to accept `string | null` instead of just `string`.

### Fixed
- Resolved an issue where `permission` could return `'0000'`.

### Added
- Added a `$chmod` parameter to the `make` function.

## [1.0.0] - 2025-11-29
- Initial release