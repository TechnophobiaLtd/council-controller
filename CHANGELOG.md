# Changelog

All notable changes to the Council Controller plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.0] - 2025-11-10

### Added
- **Button Color Management**: Extended color system with button-specific colors
  - Button background color with CSS variable `--council-button`
  - Button text color with CSS variable `--council-button-text`
  - Button hover background color with CSS variable `--council-button-hover`
  - Button text hover color with CSS variable `--council-button-text-hover`
- WordPress Color Picker integration for new button color fields
- Frontend CSS variable output for button styling

### Changed
- Admin interface now includes 9 total color fields (up from 5)
- Enhanced color management section with button state colors
- Updated documentation with button color usage examples

### Improved
- Complete button styling control through CSS variables
- Better page builder integration for interactive elements
- Hover state customization for improved user experience

## [1.3.0] - 2025-11-10

### Added
- **Color Management System**: Configure site-wide color scheme from admin interface
  - Primary color setting with CSS variable `--council-primary`
  - Secondary color setting with CSS variable `--council-secondary`
  - Tertiary color setting with CSS variable `--council-tertiary`
  - Heading color setting with CSS variable `--council-heading`
  - Body text color setting with CSS variable `--council-body-text`
- WordPress Color Picker integration for all color fields
- Frontend CSS variable output for page builder and theme integration
- Color sanitization using `sanitize_hex_color()`

### Changed
- Admin interface now includes Color Management section
- Enhanced admin JavaScript to initialize color pickers
- CSS variables automatically injected into frontend when colors are configured

### Improved
- Page builder compatibility through CSS variable system
- Theme customization capabilities
- Centralized color management for consistent branding

## [1.2.0] - 2025-11-10

### Added
- New `tag` parameter for `[council_name]` shortcode to control HTML wrapper tag (h1, h2, h3, h4, h5, h6, p, span, or div)
- New `name_tag` parameter for `[council_info]` shortcode to control HTML tag for the council name
- New `aria_label` parameter for `[council_logo]` shortcode to improve accessibility
- Tag validation with secure fallback to default values
- Examples for using different HTML tags in shortcode documentation
- ARIA label support for better screen reader compatibility

### Changed
- `[council_name]` shortcode now supports customizable HTML tags (default remains `span` for backward compatibility)
- `[council_info]` shortcode now allows customization of the name heading tag (default remains `h2` for backward compatibility)
- `[council_logo]` shortcode now supports custom ARIA labels (defaults to council name for backward compatibility)
- Updated shortcode documentation in admin interface with new tag and aria_label parameters
- Updated README.md with comprehensive examples of tag and aria_label usage

### Improved
- Accessibility: ARIA labels can now be customized for logo images
- Accessibility: Logo links also include ARIA labels when provided

## [1.1.0] - 2025-11-10

### Added
- Shortcodes documentation section in the WordPress admin interface
- Complete reference for all three shortcodes ([council_name], [council_logo], [council_info]) with their attributes and examples
- Improved admin UI styling for shortcode reference section
- Dynamic shortcode documentation registry system that automatically updates when new shortcodes are added
- WordPress filter `council_controller_shortcode_docs` for extending shortcode documentation

### Changed
- Enhanced settings page to include regularly updated list of available shortcodes and parameters
- Users no longer need to refer to external documentation to understand how to use shortcodes
- Shortcode documentation is now centralized in `init_shortcode_docs()` method for easier maintenance

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

[1.4.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.4.0
[1.3.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.3.0
[1.2.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.2.0
[1.1.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.1.0
[1.0.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.0.0
