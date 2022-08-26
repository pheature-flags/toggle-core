<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

final class Toggle
{
    private FeatureFinder $featureFinder;

    public function __construct(FeatureFinder $featureRepository)
    {
        $this->featureFinder = $featureRepository;
    }

    public function isEnabled(string $featureId, ?ConsumerIdentity $identity = null): bool
    {
        $feature = $this->featureFinder->get($featureId);

        if (false === $feature->isEnabled()) {
            return false;
        }

        $strategies = $feature->strategies();

        if ($strategies->isEmpty() || null === $identity) {
            return true;
        }

        foreach ($strategies as $strategy) {
            if ($strategy->isSatisfiedBy($identity)) {
                return true;
            }
        }

        return false;
    }
}
