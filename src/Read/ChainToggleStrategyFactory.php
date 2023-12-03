<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidStrategyTypeGiven;

use function array_map;
use function array_merge;
use function in_array;

/**
 * @psalm-import-type WriteStrategy from \Pheature\Core\Toggle\Write\Strategy
 */
final class ChainToggleStrategyFactory implements ToggleStrategyFactory
{
    private SegmentFactory $segmentFactory;
    /** @var ToggleStrategyFactory[] */
    private array $toggleStrategyFactories;

    public function __construct(SegmentFactory $segmentFactory, ToggleStrategyFactory ...$toggleStrategyFactories)
    {
        $this->segmentFactory = $segmentFactory;
        $this->toggleStrategyFactories = $toggleStrategyFactories;
    }

    /** @param WriteStrategy $strategy */
    public function createFromArray(array $strategy): ToggleStrategy
    {
        return $this->create(
            $strategy['strategy_id'],
            $strategy['strategy_type'],
            new Segments(...array_map(
                function (array $segment): Segment {
                    return $this->segmentFactory->create(
                        $segment['segment_id'],
                        $segment['segment_type'],
                        $segment['criteria']
                    );
                },
                $strategy['segments']
            ))
        );
    }

    public function create(string $strategyId, string $strategyType, ?Segments $segments = null): ToggleStrategy
    {
        foreach ($this->toggleStrategyFactories as $toggleStrategyFactory) {
            if (in_array($strategyType, $toggleStrategyFactory->types(), true)) {
                return $toggleStrategyFactory->create($strategyId, $strategyType, $segments);
            }
        }

        throw InvalidStrategyTypeGiven::withType($strategyType);
    }

    public function types(): array
    {
        /** @psalm-suppress NamedArgumentNotAllowed */
        return array_unique(
            array_merge(
                ...array_map(
                    static fn(ToggleStrategyFactory $strategyFactory) => $strategyFactory->types(),
                    $this->toggleStrategyFactories
                )
            )
        );
    }
}
