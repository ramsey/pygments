<?php

declare(strict_types=1);

namespace Ramsey\Pygments\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Pygments\Pygments;
use ReflectionClass;
use Spatie\Snapshots\MatchesSnapshots;

use function getenv;

final class CssTest extends TestCase
{
    use MatchesSnapshots;

    private Pygments $pygments;

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

    #[DataProvider('getCssProvider')]
    public function testGetCss(string $style): void
    {
        $this->assertMatchesSnapshot($this->pygments->getCss($style));
    }

    #[DataProvider('getCssProvider')]
    public function testGetCssWithSelectorPrefix(string $style): void
    {
        $this->assertMatchesSnapshot($this->pygments->getCss($style, '.syntax'));
    }

    /**
     * @return list<array{style: string}>
     */
    public static function getCssProvider(): array
    {
        return [
            ['style' => 'default'],
        ];
    }
}
