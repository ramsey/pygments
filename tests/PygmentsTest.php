<?php

declare(strict_types=1);

namespace Ramsey\Pygments\Test;

use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Pygments\Pygments;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;

use function file_get_contents;
use function getenv;
use function preg_replace;
use function str_replace;

class PygmentsTest extends TestCase
{
    protected Pygments $pygments;

    protected function setUp(): void
    {
        $this->pygments = new Pygments((string) getenv('PYGMENTIZE_PATH'));
    }

    #[DataProvider('provideSamples')]
    public function testHighlight(
        string $input,
        string $expected,
        string $expectedL,
        string $expectedG,
        string $lexer,
    ): void {
        $this->assertSame($expectedG, $this->pygments->highlight($input, null, 'html'));
        $this->assertSame($expected, $this->pygments->highlight($input, $lexer, 'html'));
        $this->assertSame($expectedL, $this->pygments->highlight($input, null, 'html', ['linenos' => 1]));
    }

    #[DataProvider('provideCss')]
    public function testGetCss(
        string $expected,
        string $expectedA,
        string $style,
    ): void {
        $this->assertSame($expected, $this->pygments->getCss($style));
        $this->assertSame($expectedA, $this->pygments->getCss($style, '.syntax'));
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

    /**
     * @return list<array{input: string, expected: string, expectedL: string, expectedG: string, lexer: string}>
     */
    public static function provideSamples(): array
    {
        $finder = new Finder();
        $finder
            ->in(__DIR__ . '/fixtures/pygments-' . (string) getenv('PYGMENTIZE_VERSION') . '/example')
            ->name('*.in')
            ->files()
            ->ignoreVCS(true);

        $samples = [];

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $samples[] = [
                'input' => $file->getContents(),
                'expected' => (string) file_get_contents(str_replace('.in', '.out', $file->getPathname())),
                'expectedL' => (string) file_get_contents(str_replace('.in', '.linenos.out', $file->getPathname())),
                'expectedG' => (string) file_get_contents(str_replace('.in', '.guess.out', $file->getPathname())),
                'lexer' => (string) preg_replace('/\..*/', '', $file->getFilename()),
            ];
        }

        return $samples;
    }

    /**
     * @return list<array{expected: string, expectedA: string, style: string}>
     */
    public static function provideCss(): array
    {
        $finder = new Finder();
        $finder
            ->in(__DIR__ . '/fixtures/pygments-' . (string) getenv('PYGMENTIZE_VERSION') . '/css')
            ->files()
            ->ignoreVCS(true)
            ->name('*.css')
            ->notName('*.prefix.css');

        $css = [];

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $css[] = [
                'expected' => $file->getContents(),
                'expectedA' => (string) file_get_contents(str_replace('.css', '.prefix.css', $file->getPathname())),
                'style' => str_replace('.css', '', $file->getFilename()),
            ];
        }

        return $css;
    }
}
