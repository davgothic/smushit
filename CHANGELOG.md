# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Client interface to allow custom client implementations
- Guzzle HTTP client implementation
- Unit tests
- Travis-CI integration

### Changed
- Moved cURL client implementation out of the SmushIt class
- Moved cURL PHP extension check out of SmushIt class and into cURL client

## [2.0.1] - 2018-01-30
### Added
- Added CHANGELOG.md
- Installation section to README.md

### Removed
- Change log entries from README.md
- License entry from README.md

### Fixed
- Added missing DocBlock param for SmushItException constructor

## [2.0.0] - 2017-03-19
### Changed
- Updated coding standards to follow PSR1/2
- Refactored to make use of PSR4 autoloading
- Increased required PHP version to 5.5.0
- Switched to semantic versioning of releases

## [1.3] - 2016-08-10
### Changed
- Update webservice URI

## [1.2] - 2015-06-17
### Changed
- Update webservice URI

## [1.1] - 2011-04-15
### Added
- Added request throttling (Thanks [Elan Ruusamäe](https://github.com/glensc))

## [1.0] - 2011-04-14
### Added
- Initial public release
