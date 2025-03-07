<?php

declare(strict_types=1);

namespace Ramsey\Pygments\Test;

use LogicException;
use PHPUnit\Framework\TestCase;
use Ramsey\Pygments\ProcessBuilder;

class ProcessBuilderTest extends TestCase
{
    public function testGetProcessThrowsException(): void
    {
        $processBuilder = ProcessBuilder::create();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You must add() command arguments before calling getProcess().');

        $processBuilder->getProcess();
    }
}
