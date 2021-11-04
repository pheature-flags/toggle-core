<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use JsonSerializable;

use function array_map;

/**
 * @phpstan-import-type WriteSegment from Segment
 * @phpstan-type WriteStrategy array{strategy_id: string, strategy_type: string, segments: WriteSegment[]}
 * @psalm-type WriteStrategy array{strategy_id: string, strategy_type: string, segments: WriteSegment[]}
 */
final class Strategy implements JsonSerializable
{
    private StrategyId $strategyId;
    private StrategyType $strategyType;
    /**
     * @var Segment[]
     */
    private array $segments;

    /**
     * Strategy constructor.
     *
     * @param StrategyId   $strategyId
     * @param StrategyType $strategyType
     * @param Segment[]    $segments
     */
    public function __construct(StrategyId $strategyId, StrategyType $strategyType, array $segments = [])
    {
        $this->strategyId = $strategyId;
        $this->strategyType = $strategyType;
        $this->segments = $segments;
    }

    public function id(): StrategyId
    {
        return $this->strategyId;
    }

    public function type(): StrategyType
    {
        return $this->strategyType;
    }

    /**
     * @return Segment[]
     */
    public function segments(): array
    {
        return $this->segments;
    }

    /** @return WriteStrategy */
    public function jsonSerialize(): array
    {
        return [
            'strategy_id' => $this->strategyId->value(),
            'strategy_type' => $this->type()->value(),
            'segments' => array_map(static fn(Segment $segment) => $segment->jsonSerialize(), $this->segments()),
        ];
    }
}
