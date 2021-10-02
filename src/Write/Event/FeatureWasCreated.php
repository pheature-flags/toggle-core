<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write\Event;

final class FeatureWasCreated
{

    private string $eventType;

    private string $featureId;

    public function __construct(string $featureId)
    {
        $this->featureId = $featureId;
        $this->eventType = "FEATURE_WAS_CREATED";
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    public function featureId(): string
    {
        return $this->featureId;
    }

    public static function fromString(string $payload): self
    {
        return new self($payload);
    }
}
