<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use JsonSerializable;

/**
 * @psalm-import-type ReadSegment from Segment
 * @psalm-type ReadStrategy array{id: string, segments: array<ReadSegment>, type: string}
 */
interface ToggleStrategy extends JsonSerializable
{
    public function id(): string;
    public function type(): string;
    public function isSatisfiedBy(ConsumerIdentity $identity): bool;
    /** @return ReadStrategy */
    public function toArray(): array;
    /** @return ReadStrategy */
    public function jsonSerialize(): array;
}
