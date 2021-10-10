<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use InvalidArgumentException;

final class ChainFeatureRepository implements FeatureRepository
{
    /** @var FeatureRepository[] */
    private array $featureRepositories;

    public function __construct(FeatureRepository ...$featureRepositories)
    {
        $this->featureRepositories = $featureRepositories;
    }

    public function save(Feature $feature): void
    {
        foreach ($this->featureRepositories as $featureRepository) {
            $featureRepository->save($feature);
        }
    }

    public function remove(Feature $feature): void
    {
        foreach ($this->featureRepositories as $featureRepository) {
            $featureRepository->remove($feature);
        }
    }

    public function get(FeatureId $featureId): Feature
    {
        foreach ($this->featureRepositories as $featureRepository) {
            try {
                $feature = $featureRepository->get($featureId);
            } catch (InvalidArgumentException $exception) {
                continue;
            }

            return $feature;
        }

        throw new InvalidArgumentException(sprintf('Feature with id %s not found.', $featureId->value()));
    }
}
