<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

/**
 * @psalm-import-type SegmentPayload from Segment
 */
interface SegmentFactory extends WithProcessableFixedTypes
{
    /** @param SegmentPayload $criteria */
    public function create(string $segmentId, string $segmentType, array $criteria): Segment;
}
