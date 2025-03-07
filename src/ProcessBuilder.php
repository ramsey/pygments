<?php

/*
 * This file was originally part of the Symfony package.
 *
 * Copyright (c) Fabien Potencier
 * Copyright (c) Ben Ramsey
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Ramsey\Pygments;

use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

final class ProcessBuilder
{
    private mixed $input = null;

    /**
     * @var list<string>
     */
    private array $prefix = [];

    /**
     * @param list<string> $arguments An array of arguments
     */
    public function __construct(private array $arguments = [])
    {
    }

    /**
     * Creates a process builder instance.
     *
     * @param list<string> $arguments An array of arguments
     */
    public static function create(array $arguments = []): self
    {
        return new self($arguments);
    }

    /**
     * Adds an unescaped argument to the command string.
     */
    public function add(string $argument): self
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Adds a prefix to the command string.
     *
     * The prefix is preserved when resetting arguments.
     *
     * @param string | list<string> $prefix A command prefix or an array of command prefixes
     */
    public function setPrefix(array | string $prefix): self
    {
        $this->prefix = (array) $prefix;

        return $this;
    }

    /**
     * Sets the arguments of the process.
     *
     * Arguments must not be escaped. Previous arguments are removed.
     *
     * @param list<string> $arguments
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Sets the input of the process.
     */
    public function setInput(mixed $input): self
    {
        $this->input = ProcessUtils::validateInput(__METHOD__, $input);

        return $this;
    }

    /**
     * Creates a Process instance and returns it.
     */
    public function getProcess(): Process
    {
        if ($this->prefix === [] && $this->arguments === []) {
            throw new LogicException('You must add() command arguments before calling getProcess().');
        }

        return new Process(
            command: [...$this->prefix, ...$this->arguments],
            input: $this->input,
        );
    }
}
