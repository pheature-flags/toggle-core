<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Core\Toggle\Read\ChainSegmentFactory;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory;
use PHPUnit\Framework\TestCase;

final class ChainSegmentFactoryTest extends TestCase
{
    private const SEGMENT_ID = 'some_segment_id';
    private const SEGMENT_TYPE = 'some_segment_type';
    private const PAYLOAD = [
        'some' => 'data',
    ];

    public function testItShouldThrowAnExceptionWhenItCantCreateAToggleStrategyType(): void
    {
        $this->expectException(InvalidSegmentTypeGiven::class);
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $chainSegmentFactory = new ChainSegmentFactory($segmentFactory);

        $chainSegmentFactory->create(self::SEGMENT_ID, self::SEGMENT_TYPE, self::PAYLOAD);
    }

    public function testItShouldBeCreatedWithAtLeastOneSegmentFactoryInstance(): void
    {
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $segmentFactory->expects(self::atLeastOnce())
            ->method('types')
            ->willReturn([self::SEGMENT_TYPE]);
        $expectedSegment = $this->createMock(Segment::class);
        $segmentFactory->expects(self::once())
            ->method('create')
            ->with(self::SEGMENT_ID, self::SEGMENT_TYPE, self::PAYLOAD)
            ->willReturn($expectedSegment);
        $chainSegmentFactory = new ChainSegmentFactory($segmentFactory);

        $current = $chainSegmentFactory->create(self::SEGMENT_ID, self::SEGMENT_TYPE, self::PAYLOAD);

        $this->assertSame($expectedSegment, $current);
        $this->assertSame([self::SEGMENT_TYPE], $chainSegmentFactory->types());
    }

    public function testItShouldCorrectlyMergeTheStrategyTypes(): void
    {
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $segmentFactory->expects(self::once())
            ->method('types')
            ->willReturn(['a', 'b']);
        $otherSegmentFactory = $this->createMock(SegmentFactory::class);
        $otherSegmentFactory->expects(self::once())
            ->method('types')
            ->willReturn(['c', 'b']);

        $chainSegmentFactory = new ChainSegmentFactory(
            $segmentFactory,
            $otherSegmentFactory
        );

        $this->assertSame(['a', 'b', 'c'], $chainSegmentFactory->types());
    }

}
