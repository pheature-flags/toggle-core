<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use JsonSerializable;

/**
 * @psalm-import-type ReadStrategies from ToggleStrategies
 * @psalm-type ReadFeature array{id: string, is_enabled: bool, strategies: array<ReadStrategies>}
 */
interface Feature extends JsonSerializable
{
    public function id(): string;
    public function strategies(): ToggleStrategies;
    public function isEnabled(): bool;
    /** @return ReadFeature */
    public function jsonSerialize(): array;
}
