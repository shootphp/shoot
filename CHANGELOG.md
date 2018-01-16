# Changelog
All notable changes to Shoot will be documented in this file.

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
