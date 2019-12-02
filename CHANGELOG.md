# Changelog

<!--
Changelog rules:
- Follow Semantic Versioning (https://semver.org/) and Keep a Changelog principles (https://keepachangelog.com/).
- There should always be "Unreleased" section at the beginning for new changelog records.
- Changelog records should be written in present imperative and end with a dot (eg. "- Improve some feature.").
-->

## Unreleased
- Fix dev dependencies
- [**BC**] Drop PHP 7.1 support

## 2.0.0 - 2019-04-15
- [**BC**] Change `Stringify::stringify` second parameter from `fullOutput` to more specific `shrinkOutput` and the default value from `false` to `true` to remain functionality of `stringify` function

## 1.1.0 - 2019-04-15
- Stringify object more verbosely - depending on object type

## 1.0.0 - 2018-11-14
- Initial version.
