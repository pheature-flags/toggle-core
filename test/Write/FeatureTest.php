<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Write;

use Pheature\Core\Toggle\Write\Event\FeatureWasDisabled;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
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

    public function testItShouldBeCreatedWithId(): void
    {
        $feature = $this->createFeature();

        $this->assertSame(self::FEATURE_ID, $feature->id());

        $this->assertFalse($feature->isEnabled());
    }

    public function testItShouldBeSerializeAsArray(): void
    {
        $feature = $this->getFeatureWithAnStrategy();

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
        $feature = $this->createFeature();
        $this->assertFalse($feature->isEnabled());
        $feature->enable();
        $this->assertTrue($feature->isEnabled());
        $events = $feature->release();
        $this->assertCount(1, $events); // Released FeatureWasCreated event
    }

    public function testItShouldBeDisabled(): void
    {
        $feature = $this->getEnabledFeature();
        $this->assertTrue($feature->isEnabled());
        $feature->disable();
        $this->assertFalse($feature->isEnabled());
        $events = $feature->release();
        $this->assertCount(2, $events); // Released FeatureWasCreated event and FeatureWasDisabled event
        $featureWasDisabledEvent = $events[1];
        $this->assertInstanceOf(FeatureWasDisabled::class, $featureWasDisabledEvent);
    }

    public function testItShouldSetAnStrategy(): void
    {
        $strategy = new Strategy(
            StrategyId::fromString(self::STRATEGY_ID),
            StrategyType::fromString(self::STRATEGY_TYPE),
            []
        );
        $feature = $this->createFeature();
        $feature->setStrategy($strategy);
        $this->assertCount(1, $feature->strategies());
        $events = $feature->release();
        $this->assertCount(1, $events); // Released FeatureWasCreated event
    }

    public function testItShouldRemoveAnStrategies(): void
    {
        $feature = $this->getFeatureWithAnStrategy();
        $this->assertCount(1, $feature->strategies());

        $feature->removeStrategy(StrategyId::fromString(self::STRATEGY_ID));
        $this->assertCount(0, $feature->strategies());
    }

    public function testItShouldStoreAFeatureWasCreatedEventWhenNewFeatureIsCreated(): void
    {
        $feature = $this->createFeature();
        $events = $feature->release();
        $this->assertCount(1, $events); // Released FeatureWasCreated event
        $featureWasCreatedEvent = $events[0];
        $this->assertInstanceOf(FeatureWasCreated::class, $featureWasCreatedEvent);
        $this->assertSame(self::FEATURE_ID, $featureWasCreatedEvent->featureId()->value());
        $this->assertInstanceOf(DatetimeImmutable::class, $featureWasCreatedEvent->occurredAt());
    }

    private function createFeature(): Feature
    {
        return Feature::withId(
            FeatureId::fromString(self::FEATURE_ID)
        );
    }

    private function getEnabledFeature(): Feature
    {
        $feature = $this->createFeature();
        $feature->enable();

        return $feature;
    }

    private function getFeatureWithAnStrategy(): Feature
    {
        $strategy = new Strategy(
            StrategyId::fromString(self::STRATEGY_ID),
            StrategyType::fromString(self::STRATEGY_TYPE),
            []
        );

        return new Feature(FeatureId::fromString(self::FEATURE_ID), false, [$strategy]);
    }
}
