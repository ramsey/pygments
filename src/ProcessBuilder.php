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

use IteratorAggregate;
use IteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

use function array_map;
use function array_merge;
use function array_shift;
use function count;
use function escapeshellarg;
use function implode;
use function is_array;

class ProcessBuilder
{
    /** @var string[] */
    private array $arguments;

    /** @var IteratorIterator|mixed|resource|string|null */
    private $input = null;

    /** @var string[] */
    private array $prefix = [];

    /**
     * @param string[] $arguments An array of arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    /**
     * Creates a process builder instance.
     *
     * @param string[] $arguments An array of arguments
     */
    public static function create(array $arguments = []): ProcessBuilder
    {
        return new ProcessBuilder($arguments);
    }

    /**
     * Adds an unescaped argument to the command string.
     *
     * @param string $argument A command argument
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
     * @param string|string[] $prefix A command prefix or an array of command prefixes
     */
    public function setPrefix($prefix): self
    {
        $this->prefix = is_array($prefix) ? $prefix : [$prefix];

        return $this;
    }

    /**
     * Sets the arguments of the process.
     *
     * Arguments must not be escaped.
     * Previous arguments are removed.
     *
     * @param string[] $arguments
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Sets the input of the process.
     *
     * @param mixed $input The input content
     */
    public function setInput($input): self
    {
        $this->input = ProcessUtils::validateInput(__METHOD__, $input);

        return $this;
    }

    /**
     * Creates a Process instance and returns it.
     *
     * @return Process & IteratorAggregate<string, string>
     */
    public function getProcess(): Process
    {
        if (count($this->prefix) === 0 && count($this->arguments) === 0) {
            throw new LogicException('You must add() command arguments before calling getProcess().');
        }

        $command = array_merge($this->prefix, $this->arguments);

        $reflectedProcess = new ReflectionClass(Process::class);

        /** @var ReflectionMethod $reflectedConstructor */
        $reflectedConstructor = $reflectedProcess->getConstructor();

        $param = $reflectedConstructor->getParameters()[0];

        /** @var ReflectionNamedType|null $type */
        $type = $param->getType();

        if ($type !== null && $type->getName() === 'array') {
            return new Process($command, null, null, $this->input);
        }

        $commandLine = array_shift($command) . ' ';
        $commandLine .= implode(' ', array_map(fn (string $v): string => escapeshellarg($v), $command));

        /**
         * @psalm-suppress InvalidArgument
         * @phpstan-ignore-next-line
         */
        return new Process($commandLine, null, null, $this->input);
    }
}
