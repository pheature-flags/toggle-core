<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use function json_decode;

/**
 * @psalm-type Criteria array<array-key, mixed>
 */
final class Payload
{
    /** @var Criteria */
    private array $criteria;

    /** @param Criteria $criteria */
    private function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /** @param Criteria $criteria */
    public static function fromArray(array $criteria): self
    {
        return new self($criteria);
    }

    public static function fromJsonString(string $jsonPayload): self
    {
        /** @var Criteria $payload */
        $payload = json_decode($jsonPayload, true, 16, JSON_THROW_ON_ERROR);

        return self::fromArray($payload);
    }

    /** @return Criteria */
    public function criteria(): array
    {
        return $this->criteria;
    }
}
