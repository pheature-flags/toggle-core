<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use function json_decode;

final class Payload
{
    /** @var array<array-key, mixed> */
    private array $criteria;

    /** @param array<array-key, mixed> $criteria */
    private function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /** @param array<array-key, mixed> $criteria */
    public static function fromArray(array $criteria): self
    {
        return new self($criteria);
    }

    public static function fromJsonString(string $jsonPayload): self
    {
        /** @var array<array-key, mixed> $payload */
        $payload = json_decode($jsonPayload, true, 16, JSON_THROW_ON_ERROR);

        return self::fromArray($payload);
    }

    /** @return array<array-key, mixed> */
    public function criteria(): array
    {
        return $this->criteria;
    }
}
