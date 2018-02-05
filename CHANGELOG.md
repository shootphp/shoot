# Changelog
All notable changes to Shoot will be documented in this file.

## [0.4.0] - 2018-02-05
- Removed Context type entirely. We'll have to settle for typing through PHPDoc, until maybe we one day have generics.

## [0.3.0] - 2018-02-01
- Context is now interfaced, in preparation of the Shoot/Http package. This package will make use of Shoot in an HTTP
context (PSR-7 and PSR-15) easier.

## [0.2.1] - 2018-01-16
- Shoot handles embedded templates by passing through all variables from the parent template.

## [0.2.0] - 2017-12-13
### Changed
- Presenters should check if a presentation model has data for themselves. This is no longer handled by the
PresenterMiddleware. This allows more granular control over presenters. For convenience, there's a HasData trait
available.
- Trying to set a variable with a non-string key will cause type errors instead of silently ignoring the variable.

## [0.1.0] - 2017-12-12
### Added
- First release!
