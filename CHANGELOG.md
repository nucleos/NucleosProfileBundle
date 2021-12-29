# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.0.0 - TBD

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.8.0 - 2021-12-29


-----

### Release Notes for [1.8.0](https://github.com/nucleos/NucleosProfileBundle/milestone/12)

Feature release (minor)

### 1.8.0

- Total issues resolved: **1**
- Total pull requests resolved: **5**
- Total contributors: **1**

#### Enhancement

 - [437: Add PHP routing files](https://github.com/nucleos/NucleosProfileBundle/pull/437) thanks to @core23
 - [431: Add type hints](https://github.com/nucleos/NucleosProfileBundle/pull/431) thanks to @core23
 - [430: Update tools and use make to run them](https://github.com/nucleos/NucleosProfileBundle/pull/430) thanks to @core23
 - [428: Normalize composer.json](https://github.com/nucleos/NucleosProfileBundle/pull/428) thanks to @core23

 - [432: Migrate routing files to PHP](https://github.com/nucleos/NucleosProfileBundle/issues/432) thanks to @core23

#### dependency

 - [429: Drop PHP 7 support](https://github.com/nucleos/NucleosProfileBundle/pull/429) thanks to @core23

## 1.7.0 - 2021-09-27


-----

### Release Notes for [1.7.0](https://github.com/nucleos/NucleosProfileBundle/milestone/10)

Feature release (minor)

### 1.7.0

- Total issues resolved: **0**
- Total pull requests resolved: **2**
- Total contributors: **2**

#### dependency

 - [368: Remove egulias/email-validator dependency](https://github.com/nucleos/NucleosProfileBundle/pull/368) thanks to @alexsegura

#### Enhancement

 - [333: Allow user manipulation on PROFILE&#95;EDIT&#95;SUCCESS](https://github.com/nucleos/NucleosProfileBundle/pull/333) thanks to @core23

## 1.6.0 - 2021-07-02


-----

### Release Notes for [1.6.0](https://github.com/nucleos/NucleosProfileBundle/milestone/8)

Feature release (minor)

### 1.6.0

- Total issues resolved: **0**
- Total pull requests resolved: **2**
- Total contributors: **1**

#### Enhancement

 - [325: Use default form theme](https://github.com/nucleos/NucleosProfileBundle/pull/325) thanks to @core23
 - [324: Add support for html mails](https://github.com/nucleos/NucleosProfileBundle/pull/324) thanks to @core23

## 1.5.0 - 2021-06-11


-----

### Release Notes for [1.5.0](https://github.com/nucleos/NucleosProfileBundle/milestone/6)

Feature release (minor)

### 1.5.0

- Total issues resolved: **0**
- Total pull requests resolved: **3**
- Total contributors: **2**

#### Bug

 - [314: Fix using wrong password validation message](https://github.com/nucleos/NucleosProfileBundle/pull/314) thanks to @core23

#### Enhancement

 - [313: Add intl dependency to use LocaleType](https://github.com/nucleos/NucleosProfileBundle/pull/313) thanks to @core23
 - [249: Fix French + add Spanish](https://github.com/nucleos/NucleosProfileBundle/pull/249) thanks to @alexsegura

## 1.4.0 - 2021-03-06


-----

### Release Notes for [1.4.0](https://github.com/nucleos/NucleosProfileBundle/milestone/3)

Feature release (minor)

### 1.4.0

- Total issues resolved: **0**
- Total pull requests resolved: **3**
- Total contributors: **2**

#### Bug

 - [241: Fix the mail class used for registration](https://github.com/nucleos/NucleosProfileBundle/pull/241) thanks to @lgeorget
 - [239: Fix YAML syntax error.](https://github.com/nucleos/NucleosProfileBundle/pull/239) thanks to @alexsegura

#### Enhancement

 - [238: Add a French translation for the bundle](https://github.com/nucleos/NucleosProfileBundle/pull/238) thanks to @lgeorget

## 1.3.1 - 2021-02-06


-----

### Release Notes for [1.3.1](https://github.com/nucleos/NucleosProfileBundle/milestone/1)



### 1.3.1

- Total issues resolved: **0**
- Total pull requests resolved: **1**
- Total contributors: **1**

#### Bug

 - [196: Fix DI for ConfirmRegistrationAction](https://github.com/nucleos/NucleosProfileBundle/pull/196) thanks to @core23

## 1.3.0 - 2021-01-17

### Changes

- Added custom event to fix email confirmation system [@fkrauthan] ([#156])

### 🐛 Bug Fixes

- Fix `RegistrationConfirmedAction` autowiring [@core23] ([#172])

## 1.2.0

### Changes

- Move submit buttons to form definition [@core23] ([#134])

### 🚀 Features

- Move configuration to PHP [@core23] ([#28])

### 🐛 Bug Fixes

- Delegate validation constraints to registration [@core23] ([#141])
- Fix wrong EmailConfirmationListener argument [@seizan8] ([#35])

### 📦 Dependencies

- Add support for PHP 8 [@core23] ([#117])

## 1.1.1

### Changes

### 🐛 Bug Fixes

- Fix check for custom profile model [@core23] ([#15])

## 1.1.0

### Changes

### 🚀 Features

- Use bootstrap 3 layout as default form theme [@core23] ([#13])
- Set translation domain for whole form [@core23] ([#12])
- Make from mail optional [@core23] ([#10])

### 🐛 Bug Fixes

- Fix profile editing [@core23] ([#14])

[#15]: https://github.com/nucleos/NucleosProfileBundle/pull/15
[#14]: https://github.com/nucleos/NucleosProfileBundle/pull/14
[#13]: https://github.com/nucleos/NucleosProfileBundle/pull/13
[#12]: https://github.com/nucleos/NucleosProfileBundle/pull/12
[#10]: https://github.com/nucleos/NucleosProfileBundle/pull/10
[@core23]: https://github.com/core23
[#141]: https://github.com/nucleos/NucleosProfileBundle/pull/141
[#134]: https://github.com/nucleos/NucleosProfileBundle/pull/134
[#117]: https://github.com/nucleos/NucleosProfileBundle/pull/117
[#35]: https://github.com/nucleos/NucleosProfileBundle/pull/35
[#28]: https://github.com/nucleos/NucleosProfileBundle/pull/28
[@seizan8]: https://github.com/seizan8
[#172]: https://github.com/nucleos/NucleosProfileBundle/pull/172
[#156]: https://github.com/nucleos/NucleosProfileBundle/pull/156
[@fkrauthan]: https://github.com/fkrauthan
