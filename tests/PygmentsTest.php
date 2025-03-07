<?php

declare(strict_types=1);

namespace Ramsey\Pygments\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Pygments\Pygments;
use ReflectionMethod;
use RuntimeException;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Process\Process;

use function getenv;

class PygmentsTest extends TestCase
{
    use MatchesSnapshots;

    protected Pygments $pygments;

    protected function setUp(): void
    {
        $this->pygments = new Pygments((string) getenv('PYGMENTIZE_PATH'));
    }

    public function testGetLexers(): void
    {
        $lexers = $this->pygments->getLexers();

        $this->assertArrayHasKey('python', $lexers);
    }

    public function testGetFormatters(): void
    {
        $formatters = $this->pygments->getFormatters();

        $this->assertArrayHasKey('html', $formatters);
    }

    public function testGetStyles(): void
    {
        $styles = $this->pygments->getStyles();

        $this->assertArrayHasKey('monokai', $styles);
    }

    public function testGetOutputThrowsExceptionWhenProcessNotSuccessful(): void
    {
        $process = Mockery::mock(Process::class);
        $process->shouldReceive('stop');
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(false);
        $process->shouldReceive('getErrorOutput')->once()->andReturn('foobar');

        $getOutput = new ReflectionMethod(Pygments::class, 'getOutput');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('foobar');

        $getOutput->invoke($this->pygments, $process);
    }

    public function testGuessLexer(): void
    {
        $this->assertSame('php', $this->pygments->guessLexer('index.php'));
        $this->assertSame('go', $this->pygments->guessLexer('main.go'));
    }
}
