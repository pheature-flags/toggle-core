<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use JsonSerializable;

/**
 * @psalm-type SegmentPayload array<mixed>
 * @psalm-type ReadSegment array{id: string, type: string, criteria: SegmentPayload}
 */
interface Segment extends JsonSerializable
{
    public function id(): string;
    public function type(): string;
    /** @return SegmentPayload */
    public function criteria(): array;
    /** @param SegmentPayload $payload */
    public function match(array $payload): bool;
    /** @return ReadSegment */
    public function toArray(): array;
    /** @return ReadSegment */
    public function jsonSerialize(): array;
}
