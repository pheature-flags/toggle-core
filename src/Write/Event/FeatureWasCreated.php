<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write\Event;

use Pheature\Core\Toggle\Write\FeatureId;
use DatetimeImmutable;

final class FeatureWasCreated
{
    private string $featureId;
    private DatetimeImmutable $occurredAt;

    public function __construct(string $featureId, DatetimeImmutable $occurredAt)
    {
        $this->featureId = $featureId;
        $this->occurredAt = $occurredAt;
    }

    public static function occur(string $featureId): self
    {
        return new self($featureId, new DatetimeImmutable());
    }

    public function featureId(): FeatureId
    {
        return FeatureId::fromString($this->featureId);
    }

    public function occurredAt(): DatetimeImmutable
    {
        return $this->occurredAt;
    }
}
