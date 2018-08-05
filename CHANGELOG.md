# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.1] - 2018-08-05
### Changed
- Tidied up code style
- Added ext-json to composer.json requirements in place of extension_loaded check

## [3.0.0] - 2018-02-10
### Added
- Added client interface to allow custom client implementations
- Added Guzzle HTTP client implementation
- Added unit tests
- Added Travis-CI integration

### Changed
- Moved cURL client implementation out of the SmushIt class
- Moved cURL PHP extension check out of SmushIt class and into cURL client

## [2.0.1] - 2018-01-30
### Added
- Added CHANGELOG.md
- Added installation section to README.md

### Removed
- Removed change log entries from README.md
- Removed License entry from README.md

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
- Updated webservice URI

## [1.2] - 2015-06-17
### Changed
- Updated webservice URI

## [1.1] - 2011-04-15
### Added
- Added request throttling (Thanks [Elan Ruusamäe](https://github.com/glensc))

## [1.0] - 2011-04-14
### Added
- Initial public release
