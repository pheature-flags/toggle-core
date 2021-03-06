<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\FeatureNotFoundException;
use Pheature\Core\Toggle\Exception\FeatureNotFoundInChainException;
use Pheature\Core\Toggle\Read\ChainFeatureFinder;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;
use PHPUnit\Framework\TestCase;

class ChainFeatureFinderTest extends TestCase
{
    private const FEATURE_ID = 'my_feature';

    public function testItShouldThrowAnExceptionWhenFeatureCannotBeFoundInAnyFeatureFinder(): void
    {
        $featureNotFoundException = $this->createMock(FeatureNotFoundException::class);

        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('get')
            ->willThrowException($featureNotFoundException);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::once())
            ->method('get')
            ->willThrowException($featureNotFoundException);

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $this->expectException(FeatureNotFoundInChainException::class);

        $chain->get(self::FEATURE_ID);
    }

    public function testItShouldGetTheFeatureByFeatureIdFromTheFirstFinder(): void
    {
        $expectedFeature = $this->createMock(Feature::class);

        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('get')
            ->willReturn($expectedFeature);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::never())
            ->method('get');

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $actual = $chain->get(self::FEATURE_ID);

        $this->assertSame($expectedFeature, $actual);
    }

    public function testItShouldGetTheFeatureByFeatureIdFromTheSecondFinder(): void
    {
        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('get')
            ->willThrowException($this->createMock(FeatureNotFoundException::class));

        $expectedFeature = $this->createMock(Feature::class);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::once())
            ->method('get')
            ->willReturn($expectedFeature);

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $actual = $chain->get(self::FEATURE_ID);

        $this->assertSame($expectedFeature, $actual);
    }

    public function testItShouldReturnAllFeaturesFromAllFinders(): void
    {
        $firstFeature = $this->createConfiguredMock(Feature::class, ['id' => 'foo']);
        $secondFeature = $this->createConfiguredMock(Feature::class, ['id' => 'bar']);
        $thirdFeature = $this->createConfiguredMock(Feature::class, ['id' => 'fiz']);
        $fourthFeature = $this->createConfiguredMock(Feature::class, ['id' => 'baz']);

        $expectedFeatures = [
            $firstFeature,
            $secondFeature,
            $thirdFeature,
            $fourthFeature
        ];

        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([$firstFeature, $thirdFeature]);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([$secondFeature, $fourthFeature]);

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $actual = $chain->all();

        $this->assertCount(count($expectedFeatures), $actual);
    }

    public function testItShouldReturnAllFeaturesFromAllFindersWithoutRepeatingItems(): void
    {
        $firstFeature = $this->createConfiguredMock(Feature::class, ['id' => 'foo']);
        $secondFeature = $this->createConfiguredMock(Feature::class, ['id' => 'bar']);
        $thirdFeature = $this->createConfiguredMock(Feature::class, ['id' => 'fiz']);
        $fourthFeature = $this->createConfiguredMock(Feature::class, ['id' => 'baz']);

        $expectedFeatures = [
            $firstFeature,
            $secondFeature,
            $thirdFeature,
            $fourthFeature
        ];

        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([$firstFeature, $thirdFeature]);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([$firstFeature, $secondFeature, $fourthFeature]);

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $actual = $chain->all();

        $this->assertCount(count($expectedFeatures), $actual);
    }

    public function testItShouldReturnAllFeaturesFromAllFindersOrderedByFirstFinderFirst(): void
    {
        $firstFeature = $this->createConfiguredMock(Feature::class, ['id' => 'foo']);
        $secondFeature = $this->createConfiguredMock(Feature::class, ['id' => 'bar']);
        $thirdFeature = $this->createConfiguredMock(Feature::class, ['id' => 'fiz']);
        $fourthFeature = $this->createConfiguredMock(Feature::class, ['id' => 'baz']);

        $expectedFeatures = [
            $firstFeature,
            $thirdFeature,
            $secondFeature,
            $fourthFeature
        ];

        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([$firstFeature, $thirdFeature]);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([$firstFeature, $secondFeature, $fourthFeature]);

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $actual = $chain->all();

        $this->assertSame($expectedFeatures, $actual);
    }

    public function testItShouldReturnAnEmptyArrayIfNoFeaturesFoundInFinders(): void
    {
        $firstFeatureFinder = $this->createMock(FeatureFinder::class);
        $firstFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([]);

        $secondFeatureFinder = $this->createMock(FeatureFinder::class);
        $secondFeatureFinder
            ->expects(self::once())
            ->method('all')
            ->willReturn([]);

        $chain = new ChainFeatureFinder(
            $firstFeatureFinder,
            $secondFeatureFinder
        );

        $actual = $chain->all();

        $this->assertSame([], $actual);
    }
}
