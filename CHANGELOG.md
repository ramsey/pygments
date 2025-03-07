# ramsey/pygments Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 3.0.0 - 2025-03-07

### Added

- Introduced a `PygmentizeProcessFailed` exception that implements the newly introduced `PygmentsError` interface.

### Changed

- Minimum required version of PHP is 8.2.

- Minimum required version of symfony/process is 7.1.7.

- The `Pygments` and `ProcessBuilder` classes are now marked `final`.

- When `pygmentize` results in a failure, this library now throws `PygmentizeProcessFailed` instead of a plain `RuntimeException`.

- Updated code to use PHP 8.2 features, such as readonly classes.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.0.2 - 2021-03-07

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Exclude `composer.lock` from distributions.

## 2.0.1 - 2021-03-07

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Support symfony/process version 5.

## 2.0.0 - 2021-03-07

### Added

- Support PHP 8.0.
- Add type hinting for parameters and return types.

### Changed

- Bump the lowest version of PHP support to version 7.3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.0.1 - 2017-03-27

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Allow use of library on PHP >=5.5.9 (fixes [#2](https://github.com/ramsey/pygments/issues/2))

## 1.0.0 - 2017-03-25

### Initial release

- Project forked from [Pygments.php](https://github.com/kzykhys/Pygments.php)
- Package name changed to ramsey/pygments
- Namespace changed to Ramsey
- Library updated to require PHP 5.6+
- Library updated to require symfony/process 3.0+
- Tests updated to ensure library works with various versions of Pygments and Python
- Re-released as 1.0.0 due to new package and namespace names
