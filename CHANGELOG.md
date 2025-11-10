# Changelog

All notable changes to the Council Controller plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.8.0] - 2025-11-10

### Added
- **Hero Image Field**: New hero image management for background/banner images
  - Admin interface field for uploading hero image from media library
  - Stores attachment ID in database
  - Media library integration with preview and upload/remove buttons
- **`[council_hero_image]` Shortcode**: Returns just the image URL (no HTML markup)
  - Perfect for use in CSS background-image properties
  - Ideal for PHP background styles
  - Supports size parameter: thumbnail, medium, large, full (default: full)
  - Example usage: `<?php $image_url = do_shortcode('[council_hero_image]'); ?>`
- Public API methods for programmatic access:
  - `Council_Controller::get_hero_image_url( $size )` - Get hero image URL at specified size
  - `Council_Controller::get_hero_image_id()` - Get hero image attachment ID
- JavaScript handlers for hero image upload and removal
- Enhanced admin interface with hero image preview (max-width: 400px)

### Changed
- Updated JavaScript to handle separate hero image uploader instance
- Extended settings sanitization to include hero image field
- Admin interface now includes hero image field with full media library integration

### Improved
- Better support for background/banner image scenarios
- Clean URL output for flexible usage in themes and page builders
- Follows same pattern as existing logo field for consistency

## [1.7.0] - 2025-11-10

### Added
- **Prepend/Append Parameters**: New `prepend` and `append` parameters for text-based shortcodes
  - `[council_name]` shortcode now supports `prepend` and `append` attributes
  - `[council_info]` shortcode now supports `prepend` and `append` attributes for the name portion
  - Enables custom text before/after council name (e.g., "Welcome to [Council Name]")
- Enhanced shortcode documentation with prepend/append examples
- Proper HTML escaping for prepend/append text

### Changed
- Updated shortcode examples to demonstrate prepend/append usage
- Enhanced admin interface documentation with new parameter examples

### Improved
- Better flexibility for displaying council name in context
- Support for custom headings with surrounding text
- Maintains proper spacing between prepend/append text and council name

## [1.6.0] - 2025-11-10

### Added
- **Menu Link Color**: New menu link color field with CSS variable `--council-menu-link`
- WordPress Color Picker integration for menu link field
- Frontend CSS variable output for menu link color
- Separate styling control for menu links vs. content links

### Changed
- Admin interface now includes 16 total color fields (3 base + 6 headings + 2 links + 1 body + 4 button)
- Enhanced color management section with menu link control
- Updated documentation with menu link color examples

### Improved
- Better navigation styling capabilities
- Separate menu and content link customization
- Improved flexibility for site-wide link styling

## [1.5.0] - 2025-11-10

### Added
- **Individual Heading Colors**: Replaced single heading color with individual H1-H6 colors
  - H1 color with CSS variable `--council-h1`
  - H2 color with CSS variable `--council-h2`
  - H3 color with CSS variable `--council-h3`
  - H4 color with CSS variable `--council-h4`
  - H5 color with CSS variable `--council-h5`
  - H6 color with CSS variable `--council-h6`
- **Link Color**: New link color field with CSS variable `--council-link`
- WordPress Color Picker integration for all new color fields
- Frontend CSS variable output for heading and link colors

### Changed
- Replaced generic `--council-heading` variable with specific heading level variables
- Admin interface now includes 16 total color fields (3 base + 6 headings + 1 link + 1 body + 4 button + 1 button text)
- Enhanced color management section with granular heading control
- Updated documentation with individual heading color examples

### Improved
- Fine-grained typography color control
- Better heading hierarchy styling capabilities
- Individual heading level customization for design flexibility
- Link color customization for improved branding

### Removed
- Generic `heading_color` field (replaced with individual h1-h6 colors)
- CSS variable `--council-heading` (replaced with `--council-h1` through `--council-h6`)

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

[1.7.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.7.0
[1.6.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.6.0
[1.5.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.5.0
[1.4.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.4.0
[1.3.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.3.0
[1.2.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.2.0
[1.1.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.1.0
[1.0.0]: https://github.com/soundbyter/council-controller/releases/tag/v1.0.0
