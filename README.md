<h1 align="center">ramsey/pygments</h1>

<p align="center">
    <strong>A PHP wrapper for Pygments, the Python syntax highlighter.</strong>
</p>

<p align="center">
    <a href="https://github.com/ramsey/pygments"><img src="https://img.shields.io/badge/source-ramsey/pygments-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/ramsey/pygments"><img src="https://img.shields.io/packagist/v/ramsey/pygments.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/ramsey/pygments.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://github.com/ramsey/pygments/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/ramsey/pygments.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://github.com/ramsey/pygments/actions/workflows/continuous-integration.yml"><img src="https://img.shields.io/github/workflow/status/ramsey/pygments/build/main?style=flat-square&logo=github" alt="Build Status"></a>
    <a href="https://codecov.io/gh/ramsey/pygments"><img src="https://img.shields.io/codecov/c/gh/ramsey/pygments?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
    <a href="https://shepherd.dev/github/ramsey/pygments"><img src="https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fshepherd.dev%2Fgithub%2Framsey%2Fpygments%2Fcoverage" alt="Psalm Type Coverage"></a>
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

* PHP 7.4 or greater (including PHP 8)
* Python
* Pygments (`pip install Pygments`)

Python and Pygments versions supported:

| Pygments:  | 2.2 | 2.3 | 2.4 | 2.5 | 2.6 | 2.7 | 2.8 |
| :--------- | :-: | :-: | :-: | :-: | :-: | :-: | :-: |
| Python 3.6 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |
| Python 3.7 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |
| Python 3.8 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |
| Python 3.9 | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   | ✔   |

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
$pygments->guessLexer('foo.rb'); // ruby
```

### Get a list of lexers/formatters/styles

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$pygments->getLexers()
$pygments->getFormatters();
$pygments->getStyles();
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
