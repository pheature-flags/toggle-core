<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Write;

use InvalidArgumentException;
use Pheature\Core\Toggle\Write\ChainFeatureRepository;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use PHPUnit\Framework\TestCase;

final class ChainFeatureRepositoryTest extends TestCase
{
    const FEATURE_ID = 'some_id';

    public function testItShouldThrowExceptionGWhenFeatureDoesNotExistInAnyOfGivenFeatureRepositories(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Feature with id some_id not found.');
        $firstRepository = $this->createMock(FeatureRepository::class);
        $firstRepository->expects(static::once())
            ->method('get')
            ->with(static::isInstanceOf(FeatureId::class))
            ->willThrowException($this->createMock(InvalidArgumentException::class));
        $secondRepository = $this->createMock(FeatureRepository::class);
        $secondRepository->expects(static::once())
            ->method('get')
            ->with(static::isInstanceOf(FeatureId::class))
            ->willThrowException($this->createMock(InvalidArgumentException::class));

        $chainFeatureRepository = new ChainFeatureRepository(
            $firstRepository,
            $secondRepository
        );

        $chainFeatureRepository->get(FeatureId::fromString(self::FEATURE_ID));
    }

    public function testItShouldCreateInstanceOfChainFeatureRepositoryAndGetSAFeatureFromGivenFeatureRepositories(): void
    {
        $feature = new Feature(
            FeatureId::fromString(self::FEATURE_ID),
            true
        );
        $firstRepository = $this->createMock(FeatureRepository::class);
        $firstRepository->expects(static::once())
            ->method('get')
            ->with(static::isInstanceOf(FeatureId::class))
            ->willThrowException($this->createMock(InvalidArgumentException::class));
        $secondRepository = $this->createMock(FeatureRepository::class);
        $secondRepository->expects(static::once())
            ->method('get')
            ->with(static::isInstanceOf(FeatureId::class))
            ->willReturn($feature);

        $chainFeatureRepository = new ChainFeatureRepository(
            $firstRepository,
            $secondRepository
        );

        $chainFeatureRepository->get(FeatureId::fromString(self::FEATURE_ID));
    }

    public function testItShouldCreateInstanceOfChainFeatureRepositoryAndSaveAFeatureInEveryGivenFeatureRepositories(): void
    {
        $feature = new Feature(
            FeatureId::fromString(self::FEATURE_ID),
            true
        );
        $firstRepository = $this->createMock(FeatureRepository::class);
        $firstRepository->expects(static::once())
            ->method('save')
            ->with($feature);
        $secondRepository = $this->createMock(FeatureRepository::class);
        $secondRepository->expects(static::once())
            ->method('save')
            ->with($feature);

        $chainFeatureRepository = new ChainFeatureRepository(
            $firstRepository,
            $secondRepository
        );

        $chainFeatureRepository->save($feature);
    }

    public function testItShouldCreateInstanceOfChainFeatureRepositoryAndRemoveAFeatureInEveryGivenFeatureRepositories(): void
    {
        $feature = new Feature(
            FeatureId::fromString(self::FEATURE_ID),
            true
        );
        $firstRepository = $this->createMock(FeatureRepository::class);
        $firstRepository->expects(static::once())
            ->method('remove')
            ->with($feature);
        $secondRepository = $this->createMock(FeatureRepository::class);
        $secondRepository->expects(static::once())
            ->method('remove')
            ->with($feature);

        $chainFeatureRepository = new ChainFeatureRepository(
            $firstRepository,
            $secondRepository
        );

        $chainFeatureRepository->remove($feature);
    }
}
    