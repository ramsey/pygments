<?php

/**
 * This file is part of the ramsey/pygments library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Kazuyuki Hayashi <hayashi@valnur.net>
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Pygments;

use Symfony\Component\Process\Process;

use function explode;
use function preg_match_all;
use function trim;

use const PREG_SET_ORDER;

/**
 * A PHP wrapper for Pygments, the Python syntax highlighter
 */
final readonly class Pygments
{
    /**
     * @param string $pygmentize The path to the `pygmentize` command.
     *     By default, this uses `pygmentize` found in the `PATH`.
     */
    public function __construct(private string $pygmentize = 'pygmentize')
    {
    }

    /**
     * Highlight the input code
     *
     * @param string $code The code to highlight
     * @param string | null $lexer The name of the lexer (php, html, ...)
     * @param string | null $formatter The name of the formatter (html, ansi, ...)
     * @param array<string, string | int> $options An array of options
     *
     * @return string The code with syntax highlighting applied, in the specified format
     */
    public function highlight(
        string $code,
        ?string $lexer = null,
        ?string $formatter = null,
        array $options = [],
    ): string {
        $builder = $this->createProcessBuilder();

        if ($lexer !== null) {
            $builder->add('-l')->add($lexer);
        } else {
            // Guess the lexer.
            $builder->add('-g');
        }

        if ($formatter !== null) {
            $builder->add('-f')->add($formatter);
        }

        foreach ($options as $key => $value) {
            $builder->add('-P')->add("$key=$value");
        }

        $process = $builder->setInput($code)->getProcess();

        return $this->getOutput($process);
    }

    /**
     * Gets style definition
     *
     * @param string $style The name of the style (default, colorful, ...)
     * @param string | null $selector A CSS selector prefix to prepend to the CSS classes
     *
     * @return string The Pygments CSS definition for the specified style
     */
    public function getCss(string $style = 'default', ?string $selector = null): string
    {
        $builder = $this->createProcessBuilder();
        $builder->add('-f')->add('html');
        $builder->add('-S')->add($style);

        if ($selector !== null) {
            $builder->add('-a')->add($selector);
        }

        return $this->getOutput($builder->getProcess());
    }

    /**
     * Guesses a lexer name based solely on the given filename
     *
     * @param string $fileName The name of a file to guess the lexer for.
     *     The file does not need to exist, or be readable; Pygments uses the
     *     file extension to guess the lexer.
     *
     * @return string The name of the lexer guessed
     */
    public function guessLexer(string $fileName): string
    {
        $process = $this->createProcessBuilder()
            ->setArguments(['-N', $fileName])
            ->getProcess();

        return trim($this->getOutput($process));
    }

    /**
     * Gets a list of lexers
     *
     * @return array<string, string>
     */
    public function getLexers(): array
    {
        $process = $this->createProcessBuilder()
            ->setArguments(['-L', 'lexer'])
            ->getProcess();

        $output = $this->getOutput($process);

        return $this->parseList($output);
    }

    /**
     * Gets a list of formatters
     *
     * @return array<string, string>
     */
    public function getFormatters(): array
    {
        $process = $this->createProcessBuilder()
            ->setArguments(['-L', 'formatter'])
            ->getProcess();

        $output = $this->getOutput($process);

        return $this->parseList($output);
    }

    /**
     * Gets a list of styles
     *
     * @return array<string, string>
     */
    public function getStyles(): array
    {
        $process = $this->createProcessBuilder()
            ->setArguments(['-L', 'style'])
            ->getProcess();

        $output = $this->getOutput($process);

        return $this->parseList($output);
    }

    private function createProcessBuilder(): ProcessBuilder
    {
        return ProcessBuilder::create()->setPrefix($this->pygmentize);
    }

    private function getOutput(Process $process): string
    {
        $process->run();

        if (!$process->isSuccessful()) {
            throw new PygmentizeProcessFailed(
                'An error occurred while running pygmentize: ' . $process->getErrorOutput(),
            );
        }

        return $process->getOutput();
    }

    /**
     * @return array<string, string>
     */
    private function parseList(string $input): array
    {
        $list = [];

        if (preg_match_all('/^\* (.*?):\r?\n *([^\r\n]*?)$/m', $input, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $names = explode(',', $match[1]);

                foreach ($names as $name) {
                    $list[trim($name)] = trim($match[2]);
                }
            }
        }

        return $list;
    }
}
