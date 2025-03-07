<?php

declare(strict_types=1);

namespace Ramsey\Pygments\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Pygments\Pygments;
use ReflectionClass;
use Spatie\Snapshots\MatchesSnapshots;

use function getenv;

final class HtmlTest extends TestCase
{
    use MatchesSnapshots;

    private Pygments $pygments;

    private const PHP_INPUT = <<<'EOD'
        <?php

        class Foo
        {
            const TEST_CONST = 1;

            public static $staticProperty = null;

            public $property = null;

            public static function staticMethod()
            {
                return new static();
            }

            public function method()
            {
                return $this;
            }

        }
        EOD;

    protected function setUp(): void
    {
        $this->pygments = new Pygments((string) getenv('PYGMENTIZE_PATH'));
    }

    protected function getSnapshotId(): string
    {
        $pygmentsVersion = (string) getenv('PYGMENTIZE_VERSION');

        return (new ReflectionClass($this))->getShortName() . '__' .
            $this->nameWithDataSet() . '__' .
            'pygments-' . $pygmentsVersion . '__' .
            $this->snapshotIncrementor;
    }

    #[DataProvider('highlightProvider')]
    public function testHighlight(string $input, string $lexer): void
    {
        $this->assertMatchesSnapshot($this->pygments->highlight($input, $lexer, 'html'));
    }

    #[DataProvider('highlightProvider')]
    public function testHighlightGuessesLexer(string $input, string $lexer): void
    {
        $this->assertMatchesSnapshot($this->pygments->highlight($input, null, 'html'));
    }

    #[DataProvider('highlightProvider')]
    public function testHighlightWithLineNumbers(string $input, string $lexer): void
    {
        $this->assertMatchesSnapshot($this->pygments->highlight($input, $lexer, 'html', ['linenos' => 1]));
    }

    /**
     * @return list<array{input: string, lexer: string}>
     */
    public static function highlightProvider(): array
    {
        return [
            [
                'input' => self::PHP_INPUT,
                'lexer' => 'php',
            ],
        ];
    }
}
