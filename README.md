<h1 align="center">ramsey/pygments</h1>

<p align="center">
    <strong>A PHP wrapper for Pygments, the Python syntax highlighter.</strong>
</p>

<p align="center">
    <a href="https://github.com/ramsey/pygments"><img src="https://img.shields.io/badge/source-ramsey/pygments-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/ramsey/pygments"><img src="https://img.shields.io/packagist/v/ramsey/pygments.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/ramsey/pygments.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://github.com/ramsey/pygments/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/ramsey/pygments.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://github.com/ramsey/pygments/actions/workflows/continuous-integration.yml"><img src="https://img.shields.io/github/actions/workflow/status/ramsey/pygments/continuous-integration.yml?branch=main&logo=github&style=flat-square" alt="Build Status"></a>
    <a href="https://codecov.io/gh/ramsey/pygments"><img src="https://img.shields.io/codecov/c/gh/ramsey/pygments?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
</p>

## About

ramsey/pygments is a PHP wrapper for [Pygments](https://pygments.org), the
Python syntax highlighter, forked from the
[Pygments.php](https://github.com/kzykhys/Pygments.php) project.

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require ramsey/pygments
```

### Requirements

* PHP 8.2 or greater
* Python
* Pygments (`pip install Pygments`)

Python and Pygments versions tested:

| Pygments:   | 2.17 | 2.18 | 2.19 |
|:------------|:----:|:----:|:----:|
| Python 3.11 |  ✔   |  ✔   |  ✔   |
| Python 3.12 |  ✔   |  ✔   |  ✔   |
| Python 3.13 |  ✔   |  ✔   |  ✔   |

> [!NOTE]
> ramsey/pygments will likely work on other versions of Python and Pygments, but
> the versions tested against are limited to keep the GitHub Actions job matrix
> at a reasonable size. If you encounter a version of Python or Pygments that
> does not work, please [open an issue](https://github.com/ramsey/pygments/issues).

## Usage

### Highlight source code

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$html = $pygments->highlight(file_get_contents('index.php'), 'php', 'html');
$console = $pygments->highlight('package main', 'go', 'ansi');
```

### Generate CSS

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$css = $pygments->getCss('monokai');
$prefixedCss = $pygments->getCss('default', '.syntax');
```

### Guess lexer name

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$lexer = $pygments->guessLexer('foo.rb'); // ruby
```

### Get a list of lexers/formatters/styles

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$lexers = $pygments->getLexers()
$formatters = $pygments->getFormatters();
$styles = $pygments->getStyles();
```

### Set a custom `pygmentize` path

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments('/path/to/pygmentize');
```

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).

## Coordinated Disclosure

Keeping user information safe and secure is a top priority, and we welcome the
contribution of external security researchers. If you believe you've found a
security issue in software that is maintained in this repository, please read
[SECURITY.md](SECURITY.md) for instructions on submitting a vulnerability report.

## Copyright and License

The ramsey/pygments library is copyright © [Ben Ramsey](https://benramsey.com)
and licensed for use under the terms of the MIT License (MIT).

ramsey/pygments is a fork of [Pygments.php](https://github.com/kzykhys/Pygments.php).
The Pygments.php library is copyright © [Kazuyuki Hayashi](https://github.com/kzykhys)
and licensed for use under the terms of the MIT License (MIT).

Please see [LICENSE](LICENSE) for more information.
