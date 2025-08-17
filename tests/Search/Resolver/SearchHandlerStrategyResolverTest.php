<?php

namespace App\Tests\Search\Resolver;

use App\Search\Exception\SearchTypeNotFoundException;
use App\Search\Handler\SearchHandlerInterface;
use App\Search\Resolver\SearchHandlerStrategyResolver;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class SearchHandlerStrategyResolverTest extends TestCase
{
    private array $strategies;
    private SearchHandlerStrategyResolver $resolver;

    /**
     * @return void
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->strategies = [
            $this->createMock(SearchHandlerInterface::class),
            $this->createMock(SearchHandlerInterface::class)
        ];

        $this->resolver = new SearchHandlerStrategyResolver($this->strategies);
    }

    /**
     * @return void
     * @throws SearchTypeNotFoundException
     */
    public function testItResolvesCorrectly(): void
    {
        $this->strategies[0]
            ->expects($this->once())
            ->method('supports')
            ->willReturn(false);

        $this->strategies[1]
            ->expects($this->once())
            ->method('supports')
            ->willReturn(true);

        $handler = $this->resolver->resolve('test');
        $this->assertInstanceOf(SearchHandlerInterface::class, $handler);
    }

    /**
     * @return void
     * @throws SearchTypeNotFoundException
     */
    public function testItThrowsSearchTypeNotFoundException(): void
    {
        $this->expectException(SearchTypeNotFoundException::class);

        $this->strategies[0]
            ->expects($this->once())
            ->method('supports')
            ->willReturn(false);

        $this->strategies[1]
            ->expects($this->once())
            ->method('supports')
            ->willReturn(false);

        $this->resolver->resolve('test');
        $this->expectException(SearchTypeNotFoundException::class);
    }
}
