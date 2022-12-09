<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write\Event;

use Pheature\Core\Toggle\Write\FeatureId;
use DateTimeImmutable;
use Pheature\Core\Toggle\Write\Payload;
use Pheature\Core\Toggle\Write\Segment;
use Pheature\Core\Toggle\Write\SegmentId;
use Pheature\Core\Toggle\Write\SegmentType;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;

/**
 * @phpstan-import-type WriteSegment from Segment
 */
final class StrategyWasAdded
{
    private string $featureId;
    private DateTimeImmutable $occurredAt;
    private string $strategyId;
    private string $strategyType;
    /** @var WriteSegment[]  */
    private array $segments;

    /** @param WriteSegment[] $segments */
    public function __construct(
        string $featureId,
        string $strategyId,
        string $strategyType,
        array $segments,
        DateTimeImmutable $occurredAt
    ) {
        $this->featureId = $featureId;
        $this->strategyId = $strategyId;
        $this->strategyType = $strategyType;
        $this->segments = $segments;
        $this->occurredAt = $occurredAt;
    }

    /** @param WriteSegment[] $segments */
    public static function occur(string $featureId, string $strategyId, string $strategyType, array $segments): self
    {
        return new self($featureId, $strategyId, $strategyType, $segments, new DateTimeImmutable());
    }

    public function featureId(): FeatureId
    {
        return FeatureId::fromString($this->featureId);
    }

    public function strategyId(): StrategyId
    {
        return StrategyId::fromString($this->strategyId);
    }

    public function strategyType(): StrategyType
    {
        return StrategyType::fromString($this->strategyType);
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    /**
     * @return Segment[]
     */
    public function segments(): array
    {
        return array_map(
            static function (array $segment): Segment {
                return new Segment(
                    SegmentId::fromString($segment['segment_id']),
                    SegmentType::fromString($segment['segment_type']),
                    Payload::fromArray($segment['criteria'])
                );
            },
            $this->segments
        );
    }
}
