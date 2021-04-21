# Release Notes for 3.x

## [Unreleased](https://github.com/litstack/litstack/compare/v3.7.0...3.x)

### Fixed

-   Fixed translations ([a8d3409](https://github.com/litstack/litstack/commit/a8d3409f3865d4fb3c3ad6f5ae7253f0e9224f4b))

## [v3.7.0](https://github.com/litstack/litstack/compare/v3.6.1...v3.7.0)

### Added

-   Global search 🔥 ([#196](https://github.com/litstack/litstack/pull/196))
-   Password reset ([0dedfd7](https://github.com/litstack/litstack/commit/0dedfd71de8843b6c3fc872a7b8bac8bf9fc4cb7))

### Fixed

-   Fixed sortable relations ([309a2c4](https://github.com/litstack/litstack/commit/309a2c49e401b70c0e532b44f6c0791837972fb6))
-   Fixed image cropper is throwing a vue error in reapeatables ([e689dd0](https://github.com/litstack/litstack/commit/e689dd0a74fc74c9189db9b34bf78d78c7c8989d))

## [v3.6.1](https://github.com/litstack/litstack/compare/v3.6.0...v3.6.1)

### Added

-   Added form field rules for crud actions ([3c9d277](https://github.com/litstack/litstack/commit/3c9d277865bbe412dee9b4e76c4225003a2fea0b), [33e8ed6](https://github.com/litstack/litstack/commit/33e8ed6445611c35b45dee9a8229320c6c67fb52))

### Fixed

-   Fixed ambiguous columns when searching ([eecd020](https://github.com/litstack/litstack/commit/eecd020a76eedd51473073a71892302e299b73c5))
-   Fixed actions that dont use modals ([89adfb8](https://github.com/litstack/litstack/commit/89adfb83fd70a85082a2993bf936df74c2f60b7f))
-   Fixed input masks ([b382988](https://github.com/litstack/litstack/commit/b3829887216d293ae183161b25205eb5e1bfd235))

## [v3.6.0](https://github.com/litstack/litstack/compare/v3.5.2...v3.6.0)

### Added

-   Added `daterange` field ([2d72292](https://github.com/litstack/litstack/commit/2d72292cfabd651f6701ea7f4cc00246d8f7985c), [7cd2923](https://github.com/litstack/litstack/commit/7cd29232cdea8d7f63babf060a0a912523a3d31b), [7cea863](https://github.com/litstack/litstack/commit/7cea8639b21526716638a4e80f2958d05f0025f7))
-   Added support for pdf download in actions ([6c7546a](https://github.com/litstack/litstack/commit/6c7546a2a489b599be0d33e3046876f5dd32e580))

### Fixed

-   Fixed relation edit button ([ea6bd08](https://github.com/litstack/litstack/commit/ea6bd089d470d22fe57d52c567c48228105c13b5))

## [v3.5.2](https://github.com/litstack/litstack/compare/v3.5.1...v3.5.2)

### Added

-   Added `badge` option for navigation entries ([9c397b7](https://github.com/litstack/litstack/commit/9c397b7663cec6de98f17751417c0c8cddb96ef9))
-   Added option to `domain` serve litstack from a certain domain ([b2b6509](https://github.com/litstack/litstack/commit/b2b65095f2fba5fa49b8a6c09eca1ff82373ce1b), [c19c69c](https://github.com/litstack/litstack/commit/c19c69c98c0450997871e5a9d35b004f35026430), [956b251](https://github.com/litstack/litstack/commit/956b251cf60cc18bfa2b2490755c20392f488d63))

### Fixed

-   Fixed select field background ([04dd7f1](https://github.com/litstack/litstack/commit/04dd7f1dc441d75a9e8c3fae2a7ae0658e156468))
-   Fixed subPages are not only shown on create ([d6e8c85](https://github.com/litstack/litstack/commit/d6e8c8544903b18a313a08e744c7e76f28d79276))
-   Fixed custom styles are not applied to index table columns ([323c14b](https://github.com/litstack/litstack/commit/323c14b163a00abea917296e140afca169652ff5))

## [v3.5.1](https://github.com/litstack/litstack/compare/v3.5.0...v3.5.1)

### Fixed

-   Fixed relation link missing in relation table ([#180](https://github.com/litstack/litstack/pull/180))

## [v3.5.0](https://github.com/litstack/litstack/compare/v3.4.5...v3.5.0)

### Added

-   Added model dependencies for all components ([#173](https://github.com/litstack/litstack/pull/173), [015c9ed](https://github.com/litstack/litstack/commit/015c9edc317a8e43f9011f634485c21cfb6f41c6))
-   Added route binding for translatable models ([#179](https://github.com/litstack/litstack/pull/179))
-   Added the ability to apply a resource to cruds ([fcb4bc3](https://github.com/litstack/litstack/commit/fcb4bc3c2eb84d52ac11ab3aa68aea0424570d3c))
-   Added `whenNotIn` dependency method ([e51e066](https://github.com/litstack/litstack/commit/e51e06664cf0873b5053374102467a93cf50fa99))
-   Added `can` method that checks permission to crud config ([83d68ad](https://github.com/litstack/litstack/commit/83d68ad457c0e2ef09d77bec02eabb5cb1298d3e))
-   Added allowing `boolean` value for authorizing components ([2f4c47d](https://github.com/litstack/litstack/commit/2f4c47dd8f08ebb9333e2e3bc3ef85cdd6454267))
-   Added unauthorized actions are not rendered ([fad348f](https://github.com/litstack/litstack/commit/fad348f618a0e375a4ca6d6c0b91a70f5b53782d))
-   Added multilanguage preview ([112decc](https://github.com/litstack/litstack/commit/112decc63f3f09d6bbe8f9910e4d43449f070fe8))
-   Added index table column dependencies ([ee6b223](https://github.com/litstack/litstack/commit/ee6b22384f93cdd22126a06c8362173c0e6465d4))
-   Added `trans` and `transChoice` index table columns ([646dcad](https://github.com/litstack/litstack/commit/646dcadebea3ab5c94c4e0bbaa1b21e475702a76), [a2661ba](https://github.com/litstack/litstack/commit/a2661ba23356d03104e967a047900c2771ed2b93))
-   Added support for `morphTo` relationships in index table columns ([fd7f5e8](https://github.com/litstack/litstack/commit/fd7f5e8121be77ca362b506cd32a2c83c83c5d58))
-   Added ability to apply column options to inline field columns ([fd7f5e8](https://github.com/litstack/litstack/commit/fd7f5e8121be77ca362b506cd32a2c83c83c5d58))

### Fixed

-   Fixed scrolling on mobile ([1f0513d](https://github.com/litstack/litstack/commit/1f0513d675d16629645f64cdff59bc37cb4adb87))
-   Fixed chart `averag` total ([b619a3e](https://github.com/litstack/litstack/commit/b619a3eefd823a75151659df750b1dfb8b378612))
-   Fixed donut chart stub ([36daf0d](https://github.com/litstack/litstack/commit/36daf0dfb53068102155d9f8a01b667a7831b072))
-   Fixed relationship index table cannot be sorted ([fd7f5e8](https://github.com/litstack/litstack/commit/fd7f5e8121be77ca362b506cd32a2c83c83c5d58))


### Changed

-   Changed namespace of `Ignite\Crud\CrudResource` to `Ignite\Crud\FormResource` ([fcb4bc3](https://github.com/litstack/litstack/commit/fcb4bc3c2eb84d52ac11ab3aa68aea0424570d3c))
-   Improved index table sorting ([9dac78f](https://github.com/litstack/litstack/commit/9dac78f8240dded9c52131a1516530d0059c3476))

## [v3.4.5](https://github.com/litstack/litstack/compare/v3.4.4...v3.4.5)

### Added

-   Added `isoFormat` option to datetime field ([a48798c](https://github.com/litstack/litstack/commit/a48798ce6d781b2ad36ba8877c9a9fa9ee2c9795))
-   Added `readOnly` for `boolean`, `radio` and `checkboxes`fields ([014ac13](https://github.com/litstack/litstack/commit/014ac13e5b6e723c489bec14453c2bf9294b1c88))
-   Added `nowrap` table column option ([7c876b3](https://github.com/litstack/litstack/commit/7c876b39370d120c171d80c7ae26c5f19e6ab1ee))

### Fixed

-   Fixed crud forms using union types ([ed16f23](https://github.com/litstack/litstack/commit/ed16f2347b84964d0e803bd4fe5eae86e9a67237))
-   Fixed `date` column uses application timezone ([38420d6](https://github.com/litstack/litstack/commit/38420d63ee188e346888139a40c5f3afb8e00131))
-   Fixed relation table `unlink` button ([73ecf04](https://github.com/litstack/litstack/commit/73ecf04603c9709af324fbf93c4b172a169542e7))
-   Fixed empty `time` field not editable ([e4861a5](https://github.com/litstack/litstack/commit/e4861a5a33b7368d7b087966b8310dc4b3675c1d))
-   Fixed empty `time` field not editable ([e4861a5](https://github.com/litstack/litstack/commit/e4861a5a33b7368d7b087966b8310dc4b3675c1d))
-   Fixed `no items selected` for list field ([e4861a5](https://github.com/litstack/litstack/commit/e4861a5a33b7368d7b087966b8310dc4b3675c1d))
-   Fixed `checkboxes` acting like `radio` ([e4334d4](https://github.com/litstack/litstack/commit/e4334d4b71dde9544e44729a4e1837d6c4752bf6))
-   Fixed stacked `checkboxes` css ([c9ae4a7](https://github.com/litstack/litstack/commit/c9ae4a74265c2af02065a49e59e622f59850264a))
-   Fixed view column returning view instad of column ([9c903f4](https://github.com/litstack/litstack/commit/9c903f438d1fb468b48fe0cadae6e49d13060fcb))
-   Fixed empty avatare column is not rounded ([7cc6f1b](https://github.com/litstack/litstack/commit/7cc6f1bf0288a0479da5458ec61f46316cacb73c))
-   Fixed alphabetic table order reversed ([589bba3](https://github.com/litstack/litstack/commit/589bba3f6cd2e72b446361d55992f70859bf4715))

### Changed

-   Changed reset password screen to match login ([#175](https://github.com/litstack/litstack/pull/175))
-   Changed form gets created in `load` method of its config ([2c57597](https://github.com/litstack/litstack/commit/2c5759767c033503488099d29125d03f15e948b3))

## [v3.4.4](https://github.com/litstack/litstack/compare/v3.4.3...v3.4.4)

### Fixed

-   Fixed single actio in index table ([9ce87d1](https://github.com/litstack/litstack/commit/9ce87d11956a7bd738bcedc7a32fb79b57bf8db4))

## [v3.4.3](https://github.com/litstack/litstack/compare/v3.4.2...v3.4.3)

### Fixed

-   Fixed Range-Field display bug for min values greater than 0 ([6d1c208](https://github.com/litstack/litstack/commit/6d1c2086683095d756dc520909c501671fe8f87f))
-   Fixed multiple actions in index tables ([6024801](https://github.com/litstack/litstack/commit/6024801caca86cfe65be0d731393a30689fa7680))

### Updates / Enhancements

-   Updated v-calendar which allows time-field to be null ([e4a7036](https://github.com/litstack/litstack/commit/e4a7036cabfcc99c638ce97a06088e3f5c5810b8))

## [v3.4.2](https://github.com/litstack/litstack/compare/v3.4.1...v3.4.2)

### Added

-   Added `lit:form-macro` Command ([295cd89](https://github.com/litstack/litstack/commit/295cd89e3373c17045e4433dae1cc04598de7257), [09fdbea](https://github.com/litstack/litstack/commit/09fdbea199e52c00cbd3cb13e5195ed66d87e0bd), [4d29a4d](https://github.com/litstack/litstack/commit/4d29a4d22a4ea9bed851093f772833944349fab1))

### Fixed

-   Fixed overflow on login page ([#172](https://github.com/litstack/litstack/pull/172))
-   Fixed images not showing in nested blocks ([617dbaa](https://github.com/litstack/litstack/commit/617dbaa221976ee5b4b857fc026020afebb3243d))
-   Fixed `morphOne` relation ([d6f94b3](https://github.com/litstack/litstack/commit/d6f94b39203038711eb0ee0d16eef577a5ff8037))

## [v3.4.1](https://github.com/litstack/litstack/compare/v3.4.0...v3.4.1)

### Fixed

-   Fixed editing translatable fields when locale is not in model ([#168](https://github.com/litstack/litstack/pull/168))
-   Fixed `CrudCreate` and `CrudUpdate` ([8996d4e](https://github.com/litstack/litstack/commit/8996d4ea22df4c6624dfd69ba0d711ccf459348b))
-   Fixed chart `last` intervals not including current interval ([b38407d](https://github.com/litstack/litstack/commit/b38407dff0d47f1d11cec75835bab716373a8679), [#169](https://github.com/litstack/litstack/issues/169))

## [v3.4.0](https://github.com/litstack/litstack/compare/v3.3.3...v3.4.0)

### Added

-   Added multiple crud form functionality :fire: ([#161](https://github.com/litstack/litstack/pull/161))
-   Added the possibility to add actions anywhere on the cudshow ([49b7d1c](https://github.com/litstack/litstack/commit/49b7d1cecff0cd7caaffaabdef2cc5f703950973))
-   Added custom style for table columns ([85de72d](https://github.com/litstack/litstack/commit/85de72d7403382d3039f3a7a689a36eaf8b519b8))

### Changed

-   Changed to version of laravel-mix from v5.0 to v6.0 ([#165](https://github.com/litstack/litstack/pull/165), [7787747](https://github.com/litstack/litstack/commit/77877470636fe28c01f3fcf31447ecd86acded1a))

## Fixed

-   Fixed breaking changes in datetime field ([86bbe9b](https://github.com/litstack/litstack/commit/86bbe9bb76484da3ac3430c72ce23ab0b97db743))
-   Fixed field conditions apply after model saving ([49b7d1c](https://github.com/litstack/litstack/commit/49b7d1cecff0cd7caaffaabdef2cc5f703950973))

## [v3.3.3](https://github.com/litstack/litstack/compare/v3.3.2...v3.3.3)

### Added

-   Added formatting model attributes for page info and text field ([#159](https://github.com/litstack/litstack/pull/159))

### Fixed

-   Fixed unlink or delete colum in crud relation table ([#158](https://github.com/litstack/litstack/pull/158))
-   Fixed css ([#160](https://github.com/litstack/litstack/pull/160))

## [v3.3.2](https://github.com/litstack/litstack/compare/v3.3.1...v3.3.2)

### Added

-   Added default minute interval `5` ([a550bc2](https://github.com/litstack/litstack/commit/a550bc21e4f59073c258c443c58f617c18d604b5))
-   Added `date`, `time` and `datetime` fields as replacement for `datetime` url ([e197e28](https://github.com/litstack/litstack/commit/e197e2868e4a56b505592e681a7bf52fbac809a3))

### Fixed

-   Fixed crud action for CrudShow ([ecddd85](https://github.com/litstack/litstack/commit/ecddd8515ea28832185193ddeae80a00be2c6e5f))
-   Fixed redirecting after creating new model ([9ac2014](https://github.com/litstack/litstack/commit/9ac201472900dd595b82cd3a9947e8adef635de2))

### Changed

-   Changed url after login to intendet url ([#157](https://github.com/litstack/litstack/pull/157))

## [v3.3.1](https://github.com/litstack/litstack/compare/v3.3.0...v3.3.1)

### Added

-   Added `addMiddleware` method to Kernel ([b748b37](https://github.com/litstack/litstack/commit/b748b37eddf50b7d508cd881cfe4c89087ef3509))

## [v3.3.0](https://github.com/litstack/litstack/compare/v3.2.5...v3.3.0)

### Added

-   Added input masks ([#127](https://github.com/litstack/litstack/pull/127))
-   Added relation preview inline fields ([#143](https://github.com/litstack/litstack/pull/143), [f44f333](https://github.com/litstack/litstack/commit/f44f333e22d98c23fa851228c43ac7a45b418939))
-   Added `appends` method to Crud show page ([#135](https://github.com/litstack/litstack/pull/135))
-   Added `info` method to Crud index page ([e69a6e8](https://github.com/litstack/litstack/commit/e69a6e8a1ef99637aecdccd637d8b23c79c1b1e3))
-   Added `translation` option to the crud meta macro ([#146](https://github.com/litstack/litstack/pull/146))
-   Added new `Listing` field ([#148](https://github.com/litstack/litstack/pull/148))
-   Added `only` method to wysiwyg ([3ad7a3c](https://github.com/litstack/litstack/commit/3ad7a3ca6cd2ff4939c13429844dba1560d2191f))
-   Added password reset functionality for litstack users ([#149](https://github.com/litstack/litstack/pull/149), [8b9c658](https://github.com/litstack/litstack/commit/8b9c6582dafaab6138343f2ec76393b60cccc0e5))
-   Added crud events ([#152](https://github.com/litstack/litstack/pull/152))
-   Added raw editing in WYSIWYG ([cf8d0ce](https://github.com/litstack/litstack/commit/cf8d0ce233eec29b2858931f20cc3bee2cf61191))([07cef8a](https://github.com/litstack/litstack/commit/07cef8af7fd77b4c6092b65bd852d0b57d2d2343))
-   Added optional system info page ([#150](https://github.com/litstack/litstack/pull/150))

### Fixed

-   Fixed sort icon for index tables ([4cead36](https://github.com/litstack/litstack/commit/4cead3652f6f078d42531092f2562f8b5d5bd787))
-   Fixed table column `date` method casting `null` as carbon instance ([338dae9](https://github.com/litstack/litstack/commit/338dae90d575a960206ea8ca100390e9cae7428a))
-   Fixed crud model binding ([#141](https://github.com/litstack/litstack/pull/141))
-   Fixed script mime types ([#142](https://github.com/litstack/litstack/pull/142))
-   Fixed missing translations ([#145](https://github.com/litstack/litstack/pull/145))
-   Fixed nested block fields ([#144](https://github.com/litstack/litstack/pull/144))
-   Fixed tags in breadcrump navigation ([dd16a1a](https://github.com/litstack/litstack/commit/dd16a1a227621a5e869aee2b39dec40b3e75dd06))
-   Fixed duplicate change password link in user profile ([#151](https://github.com/litstack/litstack/pull/151))

### Changed

-   Changed field constructor ([#147](https://github.com/litstack/litstack/pull/147))
-   Disabled default input and paste rules for markdown in wysiwyg field ([3d01fa0](https://github.com/litstack/litstack/commit/3d01fa012f02cab2f312e5759eb3837e73986c37))

## [v3.2.5](https://github.com/litstack/litstack/compare/v3.2.4...v3.2.5)

### Added

-   Added `confirmDelete` method to block field ([#131](https://github.com/litstack/litstack/pull/131))

## [v3.2.4](https://github.com/litstack/litstack/compare/v3.2.3...v3.2.4)

### Added

-   Added `allowEmpty` method to route field ([#129](https://github.com/litstack/litstack/pull/129))

## [v3.2.3](https://github.com/litstack/litstack/compare/v3.2.2...v3.2.3)

### Added

-   Added `whenIn` field condition method ([#125](https://github.com/litstack/litstack/pull/125))

### Fixed

-   Fixed authentication with custom guard ([#103](https://github.com/litstack/litstack/issues/103), [#128](https://github.com/litstack/litstack/pull/128))
-   Fixed modal bug for multiple image fields ([ad56d49](https://github.com/litstack/litstack/commit/ad56d4911dee16b47750be49e3c27acc7b5a96c9))

## [v3.2.2](https://github.com/litstack/litstack/compare/v3.2.1...v3.2.2)

### Added

-   Added PHP 8.0 support ([#126](https://github.com/litstack/litstack/pull/126))

### Fixed

-   Fixed sluggable stub ([f8515e8](https://github.com/litstack/litstack/commit/f8515e8d912acfd1a3cc12648aa45bb1f1f8b000))
-   Fixed `manyRelation` and `oneRelation` field ([384f5e4](https://github.com/litstack/litstack/commit/384f5e4de12ed2d5e00cfe50862f5476c512c235), [2f04d4e](https://github.com/litstack/litstack/commit/2f04d4e22698aa6ad67d8ff5f76693bf31f2b52b))

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
