# Release Notes for 3.x

## [Unreleased](https://github.com/litstack/litstack/compare/v3.2.1...3.x)

### Added

### Fixed

### Changed

## [v3.2.1](https://github.com/litstack/litstack/compare/v3.2.0...v3.2.1)

### Fixed

-   Fixed media field crop ([#123](https://github.com/litstack/litstack/issues/123), [c4542cb](https://github.com/litstack/litstack/commit/c4542cb3028f1f229ab8d679f7dccd2873aac285))

## [v3.2.0](https://github.com/litstack/litstack/compare/v3.1.3...v3.2.0)

### Added

-   Added table column fields :fire: ([5e76e3f](https://github.com/litstack/litstack/commit/5e76e3ffb47a0bd803c79c54487d972d7a16fb8e))
-   Added changelog ([949ed52](https://github.com/litstack/litstack/commit/949ed5224da968500780f91f45b596268c9f6613))
-   Added `resource` method to `Ignite\Crud\Models\LitFormModel` ([#97](https://github.com/litstack/litstack/issues/97), [#100](https://github.com/litstack/litstack/pull/100))
-   Added `right` method to `datetime` field ([#96](https://github.com/litstack/litstack/pull/96))
-   Added boolean support for table column value options ([
    129f722](https://github.com/litstack/litstack/commit/129f722e26f5af7f386cc46e2b4aac9fe783ea49))
-   Added datetime field methods `minuteInterval` and `disableHours` ([#110](https://github.com/litstack/litstack/pull/110))
-   Added medialibrary 9 support ([720290d](https://github.com/litstack/litstack/commit/720290d4aec58d7811bb7b7dea4b9ad56e00be34))
-   Added wysiwyg field settings to `lit` config ([#120](https://github.com/litstack/litstack/pull/120))
-   Images can now be recropped after upload ([#108](https://github.com/litstack/litstack/pull/108))

### Fixed

-   Fixed relation form modal closing after saving ([62db77e](https://github.com/litstack/litstack/commit/62db77e92fe5b29d7fdd27393e7e8c3a41f4573d))
-   Fixed installation issue with custom permissions tables names ([#105](https://github.com/litstack/litstack/issues/105), [#106](https://github.com/litstack/litstack/pull/106))
-   Fixed image column `square` ([#95](https://github.com/litstack/litstack/pull/95))
-   Fixed crud config route binding ([9a2f5db](https://github.com/litstack/litstack/commit/9a2f5dbe2c6801d7b164a6ce57b564a394d68e2a))

### Changed

-   Moved litstack core ServiceProvider's to the config `lit.providers` ([#99](https://github.com/litstack/litstack/issues/99), [#101](https://github.com/litstack/litstack/pull/101))
-   Improvid ordering performance ([a7558cb](https://github.com/litstack/litstack/commit/a7558cbf014d2c58f0655cd3b25a60bce29f8db5))
