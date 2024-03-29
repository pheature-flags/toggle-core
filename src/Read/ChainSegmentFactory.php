<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;

final class ChainSegmentFactory implements SegmentFactory
{
    /** @var SegmentFactory[] */
    private array $segmentFactories;

    public function __construct(SegmentFactory ...$segmentFactories)
    {
        $this->segmentFactories = $segmentFactories;
    }

    public function create(string $segmentId, string $segmentType, array $criteria): Segment
    {
        foreach ($this->segmentFactories as $segmentFactory) {
            if (in_array($segmentType, $segmentFactory->types(), true)) {
                return $segmentFactory->create($segmentId, $segmentType, $criteria);
            }
        }

        throw InvalidSegmentTypeGiven::withType($segmentType);
    }

    public function types(): array
    {
        /** @psalm-suppress NamedArgumentNotAllowed */
        return array_unique(
            array_merge(
                ...array_map(
                    static fn(SegmentFactory $segmentFactory) => $segmentFactory->types(),
                    $this->segmentFactories
                )
            )
        );
    }
}
