# Changelog

<!--
Changelog rules:
- Follow Semantic Versioning (https://semver.org/) and Keep a Changelog principles (https://keepachangelog.com/).
- There should always be "Unreleased" section at the beginning for new changelog records.
- Changelog records should be written in present imperative and end with a dot (eg. "- Improve some feature.").
-->

## Unreleased

## 7.0.0 - 2023-12-13
- [**BC**] Require php 8.2
- Update dependencies
- Add `sprintf` function with a `%A` placeholder for `stringify` function
- Mark `stringify` constant as deprecated in favor of `sprintf(...)` syntax

## 6.0.0 - 2023-12-13
- [**BC**] Require php 8.1
- Support php 8.2 and 8.3
- Update dependencies

## 5.1.0 - 2022-01-14
- Allow php 8.1 and update dependencies

## 5.0.0 - 2021-04-06
- [**BC**] Require php 8.0 and update dependencies

## 4.0.0 - 2021-04-06
- [**BC**] Require php 7.4 and update dependencies
- [**BC**] Change `$shrinkLongOutput` default value from `true` to `false`
- Add a second parameter `$shrinkLongOutput` to the `stringify` function

## 3.0.0 - 2019-12-02
- Fix dev dependencies
- [**BC**] Drop PHP 7.1 support

## 2.0.0 - 2019-04-15
- [**BC**] Change `Stringify::stringify` second parameter from `fullOutput` to more specific `shrinkOutput` and the default value from `false` to `true` to remain functionality of `stringify` function

## 1.1.0 - 2019-04-15
- Stringify object more verbosely - depending on object type

## 1.0.0 - 2018-11-14
- Initial version.
