# Changelog

All notable changes to the Council Controller plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.14.0] - 2025-11-11

### Added
- **REST API Endpoints**: New API endpoints for reading and updating council settings
  - `GET /wp-json/council-controller/v1/settings` - Retrieve all council settings (public access)
  - `POST/PUT /wp-json/council-controller/v1/settings` - Update council settings (requires `manage_options` capability)
  - All text, image, and color fields exposed via API
  - Image fields return both attachment IDs and full URLs for convenience
  - Proper authentication and permission checks for write operations
  - Comprehensive input validation and sanitization
  - Error handling with appropriate HTTP status codes
  - Designed for migrating old council websites to template sites

### Technical Details
- Added `register_rest_routes()` method to register API endpoints
- Added `rest_get_settings()` method for GET requests (returns all fields)
- Added `rest_update_settings()` method for POST/PUT requests (validates and updates fields)
- Added `rest_permission_check()` method for authorization (requires `manage_options`)
- Added `get_rest_update_args()` method to define REST API schema
- Email validation ensures valid email format or empty value
- Image fields validate that attachment IDs correspond to actual images
- Color fields validate hex color format or empty value
- Response includes both success status and updated data

## [1.13.0] - 2025-11-11

### Added
- **Contact & Location Section**: New section with comprehensive contact and location fields
  - **Council Address**: Textarea field for main council office address
    - Accessible via `Council_Controller::get_council_address()` static method
    - `[council_address]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `council_address` available in all page builders
  - **Meeting Venue Address**: Textarea field for meeting location (if different from office)
    - Accessible via `Council_Controller::get_meeting_venue_address()` static method
    - `[meeting_venue_address]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `meeting_venue_address` available in all page builders
  - **Email Address**: Email field with validation
    - Accessible via `Council_Controller::get_email_address()` static method
    - `[email_address]` shortcode with optional mailto link (link="yes/no")
    - Custom field `email_address` available in all page builders
  - **Phone Number**: Text field for main contact number
    - Accessible via `Council_Controller::get_phone_number()` static method
    - `[phone_number]` shortcode with optional tel link (link="yes/no")
    - Custom field `phone_number` available in all page builders
  - **Clerk's Name**: Text field for parish/town clerk name
    - Accessible via `Council_Controller::get_clerk_name()` static method
    - `[clerk_name]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `clerk_name` available in all page builders
  - **Office Hours**: Textarea field for opening times
    - Accessible via `Council_Controller::get_office_hours()` static method
    - `[office_hours]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `office_hours` available in all page builders
  - **Map Embed / Coordinates**: Textarea field for map iframe or coordinates
    - Accessible via `Council_Controller::get_map_embed()` static method
    - `[map_embed]` shortcode for displaying map embeds
    - Custom field `map_embed` available in all page builders
    - Supports iframe embeds with proper sanitization

- **Governance & Meetings Section**: New section for meeting and governance information
  - **Meeting Schedule**: Text field for regular meeting frequency
    - Accessible via `Council_Controller::get_meeting_schedule()` static method
    - `[meeting_schedule]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `meeting_schedule` available in all page builders
  - **Annual Parish Meeting Date**: Text field for annual meeting schedule
    - Accessible via `Council_Controller::get_annual_meeting_date()` static method
    - `[annual_meeting_date]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `annual_meeting_date` available in all page builders

- **Miscellaneous Section**: New section for additional information
  - **County**: Text field for county location
    - Accessible via `Council_Controller::get_county()` static method
    - `[county]` shortcode with support for tag, class, prepend, and append parameters
    - Custom field `county` available in all page builders

### Technical Details
- Added 10 new database fields for contact, location, governance, and miscellaneous information
- Added corresponding render methods for admin interface with appropriate field types
- Added 11 new public static API methods for programmatic access
- Added 10 new shortcode handlers with full tag/prepend/append support
- Email field uses `sanitize_email()` for proper validation
- Address and office hours fields use `nl2br()` for proper line break rendering
- Map embed field uses `wp_kses()` with iframe whitelist for security
- Phone shortcode includes automatic tel link creation with number formatting cleanup
- Email shortcode includes automatic mailto link creation
- Updated custom fields system to include all new fields
- Updated metadata filter to provide new fields dynamically to page builders
- Updated shortcode documentation registry with comprehensive examples
- Total new fields: 10 (7 contact/location + 2 governance + 1 misc)
- Total new shortcodes: 10
- Total custom fields for page builders: 15

## [1.11.0] - 2025-11-11

### Added
- **Parish Name Field**: New text field for storing parish name separately from council name
  - Accessible via `Council_Controller::get_parish_name()` static method
  - `[parish_name]` shortcode with support for tag, class, prepend, and append parameters
  - Custom field `parish_name` available in all page builders
  
- **Parish Established Year Field**: New text field for storing the year the parish was established
  - Accessible via `Council_Controller::get_parish_established_year()` static method
  - `[parish_established_year]` shortcode with support for tag, class, prepend, and append parameters
  - Custom field `parish_established_year` available in all page builders
  
- **Title Color Field**: New color picker field for menu title text styling when logo isn't available
  - CSS variable: `--council-title-color`
  - Accessible via `Council_Controller::get_title_color()` static method
  - Custom field `council_title_color` available in all page builders
  - Hex color validation and sanitization

### Technical Details
- Added three new database fields: `parish_name`, `parish_established_year`, `title_color`
- Added corresponding render methods for admin interface
- Added public static API methods for programmatic access
- Added two new shortcode handlers with full tag/prepend/append support
- Updated custom fields system to include new parish and title color fields
- Updated metadata filter to provide new fields dynamically
- Updated CSS variable output to include title color
- Updated shortcode documentation registry
- Total color fields: 17 (added title_color)

## [1.10.0] - 2025-11-11

### Added
- **`[council_hero_background]` Shortcode**: New shortcode for wrapping content with hero image as full-width background
  - Perfect for page builder shortcode wrappers (e.g., Breakdance Shortcode Wrapper)
  - Configurable background properties:
    - `size` - Image size: thumbnail, medium, large, full (default: full)
    - `bg_size` - CSS background-size: cover, contain, auto, or custom (default: cover)
    - `bg_repeat` - CSS background-repeat: no-repeat, repeat, repeat-x, repeat-y (default: no-repeat)
    - `bg_position` - CSS background-position: center, top, bottom, left, right, or custom (default: center)
    - `bg_attachment` - CSS background-attachment: scroll, fixed, local (default: scroll)
    - `min_height` - Minimum section height (e.g., "400px", "50vh")
    - `class` - Optional CSS class for wrapper div
  - Supports nested shortcodes in content
  - Fully responsive with 100% width
  - Usage: `[council_hero_background bg_size="cover" min_height="500px"]Your content[/council_hero_background]`

### Technical Details
- Added `shortcode_hero_background()` method with comprehensive background styling controls
- All CSS properties sanitized and escaped for security
- Empty content handling (returns empty string if no hero image set)
- Documentation automatically added to admin interface
- PHPDoc comments on new method

## [1.9.1] - 2025-11-11

### Fixed
- **Custom Fields Frontend Bug**: Fixed issue where custom fields (`council_hero_image_url` and `council_logo_url`) worked in backend/editor but not on frontend
  - Root cause: Page builders were requesting custom fields before they were written to the database
  - Solution: Added `filter_post_metadata` filter to provide values dynamically when requested
  - Changed hook from `wp` to `template_redirect` for better timing
  - Custom fields now work reliably on both frontend and backend

### Technical Details
- Added new `filter_post_metadata()` method to intercept custom field requests
- Moved custom field population to `template_redirect` hook (priority 1)
- Values now provided dynamically via WordPress metadata filter system
- Ensures custom fields are always available regardless of timing

## [1.9.0] - 2025-11-11

### Added
- **Custom Fields for Page Builder Integration**: Hero image and logo URLs now automatically added as custom fields to all pages and posts
  - `council_hero_image_url` - Hero image URL (full size)
  - `council_logo_url` - Council logo URL (full size)
  - Accessible through page builder custom field/dynamic data features:
    - Elementor: Dynamic Tags > Post > Custom Field
    - Beaver Builder: Field Connections > Custom Field
    - Divi: Dynamic Content > Post Custom Field
    - Gutenberg: Block bindings or custom field blocks
  - Fields automatically update when images change in settings
  - Fields automatically remove when images are removed from settings

### Removed
- **Deprecated Global Helper Functions**: Removed PHP helper functions that weren't working well with page builders
  - Removed `council_controller_get_hero_image_url()`
  - Removed `council_controller_get_council_name()`
  - Removed `council_controller_get_logo_url()`
  - Replaced with custom fields approach for better page builder compatibility

### Changed
- Version bumped to 1.9.0 (MINOR version due to removed deprecated functions)
- Improved page builder integration approach using WordPress custom fields

## [1.8.2] - 2025-11-10

### Added
- **Global Helper Functions for Page Builders**: Direct PHP access functions without `do_shortcode()`
  - `council_controller_get_hero_image_url( $size )` - Get hero image URL
  - `council_controller_get_council_name()` - Get council name
  - `council_controller_get_logo_url( $size )` - Get logo URL
  - Compatible with Elementor Dynamic Tags, Beaver Builder PHP fields, Divi Dynamic Content
  - Simplified syntax: `$hero_url = council_controller_get_hero_image_url();`
  - No need for `do_shortcode()` or `trim()` - returns clean values ready to use

### Changed
- Enhanced documentation with page builder integration examples
- Improved function naming for better discoverability

## [1.8.1] - 2025-11-10

### Fixed
- **Hero Image Upload Bug**: Fixed media library not opening when clicking "Choose Hero Image" button
  - Added missing `media-upload` and `media-views` dependencies to JavaScript enqueue
  - Ensures WordPress media library is fully loaded before custom script runs
  - Media uploader now opens correctly and allows image selection

### Changed
- Updated script version to 1.8.1 for cache busting

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
