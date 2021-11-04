<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use Pheature\Core\Toggle\Write\Event\FeatureWasEnabled;
use Pheature\Core\Toggle\Write\Event\FeatureWasDisabled;
use Pheature\Core\Toggle\Write\Event\FeatureWasCreated;
use JsonSerializable;
use Pheature\Core\Toggle\Write\Event\FeatureWasRemoved;
use Pheature\Core\Toggle\Write\Event\StrategyWasAdded;
use Pheature\Core\Toggle\Write\Event\StrategyWasRemoved;

use function array_map;
use function array_values;

final class Feature implements JsonSerializable
{
    private FeatureId $featureId;
    private bool $enabled;
    /** @var Strategy[] */
    private array $strategies = [];
    /** @var array<object>  */
    private array $events = [];

    /**
     * Feature constructor.
     *
     * @param FeatureId  $featureId
     * @param bool       $enabled
     * @param Strategy[] $strategies
     */
    public function __construct(FeatureId $featureId, bool $enabled, array $strategies = [])
    {
        $this->featureId = $featureId;
        $this->enabled = $enabled;
        foreach ($strategies as $strategy) {
            $this->strategies[$strategy->id()->value()] = $strategy;
        }
    }

    /**
     * @return object[]
     */
    public function release(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    public static function withId(FeatureId $featureId): self
    {
        $feature = new self($featureId, false);
        $feature->events[] = FeatureWasCreated::occur($featureId->value());

        return $feature;
    }

    public function setStrategy(Strategy $strategy): void
    {
        $this->strategies[$strategy->id()->value()] = $strategy;
        $this->events[] = StrategyWasAdded::occur(
            $this->featureId->value(),
            $strategy->id()->value(),
            $strategy->type()->value(),
            array_map(static fn(Segment $segment) => $segment->jsonSerialize(), $strategy->segments())
        );
    }

    public function removeStrategy(StrategyId $strategyId): void
    {
        unset($this->strategies[$strategyId->value()]);
        $this->events[] = StrategyWasRemoved::occur($this->featureId->value(), $strategyId->value());
    }

    public function enable(): void
    {
        $this->enabled = true;
        $this->events[] = FeatureWasEnabled::occur($this->featureId->value());
    }

    public function disable(): void
    {
        $this->enabled = false;
        $this->events[] = FeatureWasDisabled::occur($this->featureId->value());
    }

    public function remove(): void
    {
        $this->events[] = FeatureWasRemoved::occur($this->featureId->value());
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function id(): string
    {
        return $this->featureId->value();
    }

    /**
     * @return Strategy[]
     */
    public function strategies(): array
    {
        return array_values($this->strategies);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'feature_id' => $this->featureId->value(),
            'enabled' => $this->enabled,
            'strategies' => array_map(static fn(Strategy $strategy) => $strategy->jsonSerialize(), $this->strategies),
        ];
    }
}
