<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write\Event;

use DateTimeImmutable;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\StrategyId;

final class StrategyWasRemoved
{
    private string $featureId;
    private string $strategyId;
    private DatetimeImmutable $occurredAt;

    public function __construct(string $featureId, string $strategyId, DatetimeImmutable $occurredAt)
    {
        $this->featureId = $featureId;
        $this->strategyId = $strategyId;
        $this->occurredAt = $occurredAt;
    }

    public static function occur(string $featureId, string $strategyId): self
    {
        return new self($featureId, $strategyId, new DatetimeImmutable());
    }

    public function featureId(): FeatureId
    {
        return FeatureId::fromString($this->featureId);
    }

    public function strategyId(): StrategyId
    {
        return StrategyId::fromString($this->strategyId);
    }

    public function occurredAt(): DatetimeImmutable
    {
        return $this->occurredAt;
    }
}
