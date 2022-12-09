<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write\Event;

use Pheature\Core\Toggle\Write\FeatureId;
use DateTimeImmutable;

final class FeatureWasDisabled
{
    private string $featureId;
    private DateTimeImmutable $occurredAt;

    public function __construct(string $featureId, DateTimeImmutable $occurredAt)
    {
        $this->featureId = $featureId;
        $this->occurredAt = $occurredAt;
    }

    public static function occur(string $featureId): self
    {
        return new self($featureId, new DateTimeImmutable());
    }

    public function featureId(): FeatureId
    {
        return FeatureId::fromString($this->featureId);
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
