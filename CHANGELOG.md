# Changelog
All notable changes to Shoot will be documented in this file.

## [3.1.0] - 2019-06-18
### Changed
- Twig has been bumped to v2.11.

## [3.0.0] - 2019-05-01
### Changed
- Shoot now requires Twig v2.9. In addition, it's now also pinned to this minor version, as Twig doesn't seem to follow
SemVer with regards to non-breaking changes in its APIs. As this on its own is a breaking change for Shoot, this
warrants a major version bump.

### Fixed
- Compatibility issues with Twig v2.9 have been fixed.

## [2.0.0] - 2019-03-04
### Added
- Documentation on nesting presentation models and the `optional` tag.
- An `Installer` class which sets up Shoot for an instance of Twig.

### Changed
- Shoot now requires PHP 7.2.
- The Twig dependency has been bumped to v2.6.
- PHPUnit has been bumped to v8.0, and all tests have been updated accordingly.
- The `SuppressionMiddleware` is no longer enabled by the default. You'll have to pass it to the `Pipeline` constructor
along with any other middleware you use.
- The `LoggingMiddleware` now also logs errors. Kind of odd it did not do that before.

### Fixed
- The optional tag would still output any contents from before the exception was thrown. This is now fixed.
- Models now work as expected when using extends, embed and blocks – removing what was previously a limitation of Shoot.


## [1.0.0] - 2018-08-27
### Added
- The `optional` tag was added. This allows runtime exceptions to be suppressed so parts which are not essential to the
page can be left out in case of failure.  
- The HTTP middleware from the Shoot/Http package is now included with Shoot as `ShootMiddleware`. 
- Added `getVariable` to `PresentationModel` to read a single variable from the presentation model.

### Changed
- The generic context has been replaced with the PSR-7 request object as it seems to make the most sense in practice. 
- The actual Twig extension has been split off from the `Pipeline` class.
- The `getPresenter` method of `HasPresenterInterface` was renamed to `getPresenterName` as it more accurately describes
its purpose. 
- `HasDataTrait` has been moved to a `Utilities` namespace.
- The Twig dependency has been bumped to v2.5.
- Lots of housekeeping in code and documentation.

### Deprecated
- The Shoot/Http package is deprecated as of this release.

## [0.4.0] - 2018-02-05
### Changed
- Removed `Context` type entirely. We'll have to settle for typing through PHPDoc, until maybe we one day have generics.

## [0.3.0] - 2018-02-01
### Changed
- `Context` is now interfaced, in preparation of the Shoot/Http package. This package will make use of Shoot in an HTTP
context (PSR-7 and PSR-15) easier.

## [0.2.1] - 2018-01-16
### Fixed
- Shoot handles embedded templates by passing through all variables from the parent template.

## [0.2.0] - 2017-12-13
### Changed
- Presenters should check if a presentation model has data, and whether to act on that. This is no longer handled by the
`PresenterMiddleware`. This allows more granular control over presenters. For convenience, there's a `HasDataTrait`
available.
- Trying to set a variable with a non-string key will cause type errors instead of silently ignoring the variable.

## [0.1.0] - 2017-12-12
### Added
- First release!
