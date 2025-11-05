# Changelog

All notable changes to the Council Controller plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-11-05

### Added
- Initial release of Council Controller Must-Use WordPress plugin
- Admin settings page accessible via Dashboard → Council Settings
- Council Name text field with sanitization
- Council Logo field with WordPress Media Library integration
- Settings persistence using WordPress Settings API
- **Three shortcodes for displaying council information:**
  - `[council_name]` - Display council name with optional CSS class
  - `[council_logo]` - Display council logo with customizable size, class, and optional link
  - `[council_info]` - Display both name and logo together with flexible options
- Public API methods for accessing council settings:
  - `Council_Controller::get_council_name()`
  - `Council_Controller::get_council_logo_url()`
  - `Council_Controller::get_council_logo_id()`
- JavaScript media uploader with localized strings for i18n support
- Admin interface styling
- Proper input sanitization and output escaping
- Translation-ready strings (i18n)
- Capability checks for admin access
- Documentation (README.md, AGENTS.md, CONTRIBUTING.md)

### Security
- All user inputs sanitized using WordPress functions
- All outputs escaped using appropriate WordPress functions
- Capability checks enforced (`manage_options`)
- Direct file access prevention via `ABSPATH` check
- Proper handling of attachment URL false returns

[1.0.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.0.0
