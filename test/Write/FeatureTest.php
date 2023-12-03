<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Write;

use Pheature\Core\Toggle\Write\Event\FeatureWasDisabled;
use Pheature\Core\Toggle\Write\Event\FeatureWasEnabled;
use Pheature\Core\Toggle\Write\Event\FeatureWasRemoved;
use Pheature\Core\Toggle\Write\Event\StrategyWasAdded;
use Pheature\Core\Toggle\Write\Event\StrategyWasRemoved;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\Payload;
use Pheature\Core\Toggle\Write\Segment;
use Pheature\Core\Toggle\Write\SegmentId;
use Pheature\Core\Toggle\Write\SegmentType;
use Pheature\Core\Toggle\Write\Strategy;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;
use Pheature\Core\Toggle\Write\Event\FeatureWasCreated;
use DatetimeImmutable;
use PHPUnit\Framework\TestCase;

final class FeatureTest extends TestCase
{
    private const FEATURE_ID = 'some_feature';
    private const STRATEGY_ID = 'some_strategy';
    private const STRATEGY_TYPE = 'some_strategy_type';
    private const SEGMENT_ID = 'some_segment';
    private const SEGMENT_TYPE = 'some_segment_type';

    public function testItShouldBeCreatedWithId(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));

        $this->assertSame(self::FEATURE_ID, $feature->id());

        $this->assertFalse($feature->isEnabled());
    }

    public function testItShouldBeSerializeAsArray(): void
    {
        $feature = $this->createFeatureWithAnStrategy();

        $this->assertSame([
            'feature_id' => self::FEATURE_ID,
            'enabled' => false,
            'strategies' => [
                self::STRATEGY_ID => [
                    'strategy_id' => self::STRATEGY_ID,
                    'strategy_type' => self::STRATEGY_TYPE,
                    'segments' => [],
                ],
            ],
        ], $feature->jsonSerialize());
    }

    public function testItShouldBeEnabled(): void
    {
        $feature = $this->createFeature(false);
        $this->assertFalse($feature->isEnabled());
        $feature->enable();
        $this->assertTrue($feature->isEnabled());

        $events = $feature->release();
        $this->assertCount(1, $events);
        $this->assertEventIsRecorded(FeatureWasEnabled::class, $events);

        $featureWasEnabledEvent = $events[0];
        $this->assertInstanceOf(FeatureWasEnabled::class, $featureWasEnabledEvent);
        $this->assertSame(self::FEATURE_ID, $featureWasEnabledEvent->featureId()->value());
        $this->assertInstanceOf(DatetimeImmutable::class, $featureWasEnabledEvent->occurredAt());
    }

    public function testItShouldBeDisabled(): void
    {
        $feature = $this->createFeature(true);
        $this->assertTrue($feature->isEnabled());
        $feature->disable();
        $this->assertFalse($feature->isEnabled());
        $events = $feature->release();

        $this->assertCount(1, $events);
        $this->assertEventIsRecorded(FeatureWasDisabled::class, $events);

        $featureWasDisabledEvent = $events[0];
        $this->assertSame(self::FEATURE_ID, $featureWasDisabledEvent->featureId()->value());
        $this->assertInstanceOf(DatetimeImmutable::class, $featureWasDisabledEvent->occurredAt());
    }

    public function testItShouldSetAnStrategy(): void
    {
        $strategy = new Strategy(
            StrategyId::fromString(self::STRATEGY_ID),
            StrategyType::fromString(self::STRATEGY_TYPE),
            [
                new Segment(
                    SegmentId::fromString(self::SEGMENT_ID),
                    SegmentType::fromString(self::SEGMENT_TYPE),
                    Payload::fromArray(['foo' => 'bar'])
                )
            ]
        );
        $feature = $this->createFeature();
        $feature->setStrategy($strategy);
        $this->assertCount(1, $feature->strategies());

        $events = $feature->release();
        $this->assertCount(1, $events);
        $this->assertEventIsRecorded(StrategyWasAdded::class, $events);

        /** @var StrategyWasAdded $strategyWasAdded */
        $strategyWasAdded = $events[0];
        $this->assertSame(self::FEATURE_ID, $strategyWasAdded->featureId()->value());
        $this->assertSame(self::STRATEGY_ID, $strategyWasAdded->strategyId()->value());
        $this->assertSame(self::STRATEGY_TYPE, $strategyWasAdded->strategyType()->value());
        /** @var Segment $segment */
        $segment = $strategyWasAdded->segments()[0];
        $this->assertSame(self::SEGMENT_ID, $segment->segmentId()->value());
        $this->assertSame(self::SEGMENT_TYPE, $segment->segmentType()->value());
        $this->assertSame(['foo' => 'bar'], $segment->payload()->criteria());
        $this->assertInstanceOf(DatetimeImmutable::class, $strategyWasAdded->occurredAt());
    }

    public function testItShouldRemoveAnStrategy(): void
    {
        $feature = $this->createFeatureWithAnStrategy();
        $this->assertCount(1, $feature->strategies());

        $feature->removeStrategy(StrategyId::fromString(self::STRATEGY_ID));
        $this->assertCount(0, $feature->strategies());

        $events = $feature->release();
        $this->assertCount(1, $events);
        $this->assertEventIsRecorded(StrategyWasRemoved::class, $events);
        $strategyWasRemoved = $events[0];
        $this->assertSame(self::FEATURE_ID, $strategyWasRemoved->featureId()->value());
        $this->assertSame(self::STRATEGY_ID, $strategyWasRemoved->strategyId()->value());
        $this->assertInstanceOf(DatetimeImmutable::class, $strategyWasRemoved->occurredAt());
    }

    public function testItShouldStoreAFeatureWasCreatedEventWhenNewFeatureIsCreated(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $events = $feature->release();

        $this->assertCount(1, $events);
        $this->assertEventIsRecorded(FeatureWasCreated::class, $events);

        $featureWasCreatedEvent = $events[0];
        $this->assertSame(self::FEATURE_ID, $featureWasCreatedEvent->featureId()->value());
        $this->assertInstanceOf(DatetimeImmutable::class, $featureWasCreatedEvent->occurredAt());
    }

    public function testItShouldStoreAFeatureWasRemovedWhenItIsRemoved(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $feature->remove();

        $events = $feature->release();
        $this->assertCount(2, $events);
        $this->assertEventIsRecorded(FeatureWasRemoved::class, $events);

        $featureWasRemovedEvent = $events[1];
        $this->assertSame(self::FEATURE_ID, $featureWasRemovedEvent->featureId()->value());
        $this->assertInstanceOf(DatetimeImmutable::class, $featureWasRemovedEvent->occurredAt());
    }

    private function createFeature(?bool $enabled = null, ?array $strategies = null): Feature
    {
        return new Feature(
            FeatureId::fromString(self::FEATURE_ID),
            $enabled ?? false,
            $strategies ?? []
        );
    }

    private function createFeatureWithAnStrategy(): Feature
    {
        $strategy = new Strategy(
            StrategyId::fromString(self::STRATEGY_ID),
            StrategyType::fromString(self::STRATEGY_TYPE),
            []
        );

        return $this->createFeature(false, [$strategy]);
    }

    private function assertEventIsRecorded(string $expectedEvent, array $featureEvents): void
    {
        $matchedEvents = array_filter(
            $featureEvents,
            static fn (object $event) => $expectedEvent === get_class($event)
        );

        $this->assertNotEmpty($matchedEvents);
    }
}
