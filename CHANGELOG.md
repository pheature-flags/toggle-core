# Changelog

## [0.2.4](https://github.com/pheature-flags/toggle-core/tree/0.2.4)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.2.3...0.2.4)

**Implemented enhancements:**

- \[toggle-core\] Update PhpStan to version 1 and level 9 [\#39](https://github.com/pheature-flags/toggle-core/issues/39)
- Create AnStrategyWasRemoved event and dispatch it inside Feature Class [\#18](https://github.com/pheature-flags/toggle-core/issues/18)
- Create AnStrategyWasAdded event and dispatch it inside Feature Class [\#17](https://github.com/pheature-flags/toggle-core/issues/17)
- \[\#17\] Add StrategyWasAdded event [\#44](https://github.com/pheature-flags/toggle-core/pull/44) (@kpicaza)
- \[\#18\] Add StrategyWasRemoved event [\#43](https://github.com/pheature-flags/toggle-core/pull/43) (@kpicaza)
- \[\#39\] Update PhpStan to version 1 and level 9 [\#40](https://github.com/pheature-flags/toggle-core/pull/40) (@kpicaza)

**Closed issues:**

- \[toggle-core\] update CI matrix PHP versions [\#41](https://github.com/pheature-flags/toggle-core/issues/41)

**Merged pull requests:**

- \[\#41\] Run CI both PHP 7.4 and 8.0 [\#42](https://github.com/pheature-flags/toggle-core/pull/42) (@kpicaza)

## [0.2.3](https://github.com/pheature-flags/toggle-core/tree/0.2.3) (2021-10-26)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.2.2...0.2.3)

**Fixed bugs:**

- Remove unexisting Laminas ConfigProvider [\#37](https://github.com/pheature-flags/toggle-core/pull/37) (@xserrat)

## [0.2.2](https://github.com/pheature-flags/toggle-core/tree/0.2.2) (2021-10-19)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.2.1...0.2.2)

**Implemented enhancements:**

- Refactor merging strategy in Chain finder to avoid duplicates [\#34](https://github.com/pheature-flags/toggle-core/issues/34)
- \[\#34\] adjust chain finder merging strategy [\#36](https://github.com/pheature-flags/toggle-core/pull/36) (@kpicaza)

## [0.2.1](https://github.com/pheature-flags/toggle-core/tree/0.2.1) (2021-10-10)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.2.0...0.2.1)

**Implemented enhancements:**

- Create ChainFeatureRepository [\#32](https://github.com/pheature-flags/toggle-core/issues/32)
- \[toggle-core\] Add `ChainFeatureFinder` capable of retrieving features from multiple finders [\#14](https://github.com/pheature-flags/toggle-core/issues/14)
- \[\#32\] create and test ChainFeatureRepository [\#33](https://github.com/pheature-flags/toggle-core/pull/33) (@kpicaza)

**Merged pull requests:**

- Update infection/infection requirement from ^0.23.0 to ^0.23.0 || ^0.25.0 [\#13](https://github.com/pheature-flags/toggle-core/pull/13) (@dependabot[bot])

## [0.2.0](https://github.com/pheature-flags/toggle-core/tree/0.2.0) (2021-10-07)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.1.3...0.2.0)

**Implemented enhancements:**

- Update `FeatureRepository::remove` contract to accept `Feature` instead of `FeatureId` [\#30](https://github.com/pheature-flags/toggle-core/issues/30)
- \[\#30\] Update FeatureRepository::remove contract to accept Feature instead of FeatureId [\#31](https://github.com/pheature-flags/toggle-core/pull/31) (@xserrat)

## [0.1.3](https://github.com/pheature-flags/toggle-core/tree/0.1.3) (2021-10-06)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.1.2...0.1.3)

**Implemented enhancements:**

- Create FeatureWasRemoved event and record it inside Feature Class [\#28](https://github.com/pheature-flags/toggle-core/issues/28)
- Create FeatureWasEnabled event and dispatch it inside Feature Class [\#19](https://github.com/pheature-flags/toggle-core/issues/19)
- \[\#28\] Create FeatureWasRemoved event and record it inside Feature Class [\#29](https://github.com/pheature-flags/toggle-core/pull/29) (@xserrat)
- Add feature was enabled event [\#27](https://github.com/pheature-flags/toggle-core/pull/27) (@vblinden)

## [0.1.2](https://github.com/pheature-flags/toggle-core/tree/0.1.2) (2021-10-03)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/0.1.0...0.1.2)

**Implemented enhancements:**

- Create FeatureWasDisabled event and dispatch it inside Feature Class [\#20](https://github.com/pheature-flags/toggle-core/issues/20)
- Create FeatureWasCreated event and dispatch it inside Feature Class [\#16](https://github.com/pheature-flags/toggle-core/issues/16)
- Add the ability to save events in the write model Feature class [\#15](https://github.com/pheature-flags/toggle-core/issues/15)
- Add feature was disabled event [\#25](https://github.com/pheature-flags/toggle-core/pull/25) (@vblinden)
- \[\#15\] update tests [\#22](https://github.com/pheature-flags/toggle-core/pull/22) (@kpicaza)
- \[\#15\] add ability to collect events in Feature write model [\#21](https://github.com/pheature-flags/toggle-core/pull/21) (@kpicaza)

**Merged pull requests:**

- \[toggle-core\#16\] Create FeatureWasCreated event [\#24](https://github.com/pheature-flags/toggle-core/pull/24) (@pcs289)

## [v0.1.1](https://github.com/pheature-flags/toggle-core/tree/v0.1.1) (2021-06-19)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/3049d4ad29a92be94491ca5af81b6121aaf395ab...v0.1.1)

**Implemented enhancements:**

- add codeclimate badge [\#11](https://github.com/pheature-flags/toggle-core/pull/11) (@kpicaza)
- add mutation coverage badge [\#8](https://github.com/pheature-flags/toggle-core/pull/8) (@kpicaza)
- fix codecov link shorcuts [\#7](https://github.com/pheature-flags/toggle-core/pull/7) (@kpicaza)
- fix psalm badge link [\#6](https://github.com/pheature-flags/toggle-core/pull/6) (@kpicaza)
- add psalm badge [\#5](https://github.com/pheature-flags/toggle-core/pull/5) (@kpicaza)
- test codecov hook [\#4](https://github.com/pheature-flags/toggle-core/pull/4) (@kpicaza)
- add scrutinizer badges [\#3](https://github.com/pheature-flags/toggle-core/pull/3) (@kpicaza)
- add downloads badge [\#2](https://github.com/pheature-flags/toggle-core/pull/2) (@kpicaza)
- add version badge [\#1](https://github.com/pheature-flags/toggle-core/pull/1) (@kpicaza)

**Merged pull requests:**

- fix contributing linlk [\#10](https://github.com/pheature-flags/toggle-core/pull/10) (@kpicaza)
- fix docs link [\#9](https://github.com/pheature-flags/toggle-core/pull/9) (@kpicaza)

## [0.1.0](https://github.com/pheature-flags/toggle-core/tree/0.1.0) (2021-06-19)

[Full Changelog](https://github.com/pheature-flags/toggle-core/compare/v0.1.1...0.1.0)

## [0.0.1](https://github.com/pheature-flags/pheature-flags/tree/0.0.1) (2021-06-19)

[Full Changelog](https://github.com/pheature-flags/pheature-flags/compare/4efde1b91949256bf8d3b3baf7546150ddcc0e90...0.0.1)

**Implemented enhancements:**

- \[toggle-core\] ChainTogleStrategyFactory must implement ToggleStrategyFactory interface [\#219](https://github.com/pheature-flags/pheature-flags/issues/219)
- \[toggle-core\] Add id and type to strategy and segment read models [\#217](https://github.com/pheature-flags/pheature-flags/issues/217)


\* *This Changelog was automatically generated by [github_changelog_generator](https://github.com/github-changelog-generator/github-changelog-generator)*
