# Agents Guide for Council Controller

This document provides guidance for AI agents and automated systems working on the Council Controller WordPress plugin.

## Plugin Overview

Council Controller is a Must-Use (MU) WordPress plugin that provides an administrative interface for managing council information including name and logo. The plugin is designed to be deployed in WordPress's `mu-plugins` directory for automatic activation.

## Architecture

### Core Components

1. **Main Plugin File** (`council-controller.php`)
   - Singleton class pattern (`Council_Controller`)
   - WordPress Settings API integration
   - Admin menu registration
   - Settings sanitization and validation
   - Public API methods for accessing settings

2. **Assets**
   - `assets/js/admin.js` - Media uploader functionality
   - `assets/css/admin.css` - Admin interface styling

### Key Design Patterns

- **Singleton Pattern**: The main class uses a singleton pattern to ensure only one instance exists
- **WordPress Hooks**: Uses `admin_menu`, `admin_init`, and `admin_enqueue_scripts` actions
- **Settings API**: Leverages WordPress Settings API for data persistence
- **Media Uploader**: Integrates with WordPress Media Library via `wp.media`

## Development Guidelines

### Versioning

This plugin follows **Semantic Versioning 2.0.0** (https://semver.org/):

- **MAJOR** version when making incompatible API changes
- **MINOR** version when adding functionality in a backward compatible manner
- **PATCH** version when making backward compatible bug fixes

Current version: **1.0.0**

### Code Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Use WordPress escaping functions: `esc_html()`, `esc_attr()`, `esc_url()`
- Use WordPress sanitization functions: `sanitize_text_field()`, `absint()`
- Ensure all user-facing strings are internationalized using `__()` and `esc_html__()`

### Security Requirements

- **Input Sanitization**: All user inputs must be sanitized before saving
- **Output Escaping**: All outputs must be escaped before display
- **Capability Checks**: Admin functions must check `manage_options` capability
- **Nonces**: Forms should use WordPress nonces (currently handled by Settings API)
- **Direct Access Prevention**: All PHP files must check for `ABSPATH` constant

### Testing Considerations

While this plugin doesn't currently have automated tests, when adding tests consider:

- WordPress core functions are not available in standard PHP test environments
- Use [WordPress Unit Testing Framework](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/) or [WP_Mock](https://github.com/10up/wp_mock)
- Test sanitization functions independently
- Mock WordPress functions when necessary

## Key Functions

### Public API

These static methods provide programmatic access to settings:

```php
Council_Controller::get_council_name()        // Returns string
Council_Controller::get_council_logo_url()    // Returns string (URL or empty)
Council_Controller::get_council_logo_id()     // Returns int (attachment ID or empty)
```

### Internal Methods

- `add_admin_menu()` - Registers the admin menu page
- `register_settings()` - Registers settings fields and sections
- `sanitize_settings()` - Sanitizes input data
- `render_settings_page()` - Renders the main settings page
- `enqueue_admin_scripts()` - Conditionally loads assets

## Database Structure

### Options

Settings are stored as a serialized array in WordPress options table:

- **Option Name**: `council_controller_settings`
- **Structure**:
  ```php
  array(
      'council_name' => string,  // Council name
      'council_logo' => int      // Attachment ID for logo
  )
  ```

## WordPress Compatibility

- **Minimum WordPress Version**: 5.0
- **Minimum PHP Version**: 7.0
- **Plugin Type**: Must-Use (MU) Plugin

## File Structure

```
council-controller/
├── council-controller.php    # Main plugin file
├── assets/
│   ├── js/
│   │   └── admin.js         # Media uploader JavaScript
│   └── css/
│       └── admin.css        # Admin interface styles
├── README.md                # User documentation
├── AGENTS.md                # This file
├── CHANGELOG.md             # Version history
├── CONTRIBUTING.md          # Contribution guidelines
└── .gitignore              # Git ignore rules
```

## Making Changes

### Before Making Changes

1. Review the existing code structure
2. Understand the WordPress Settings API if modifying settings
3. Ensure changes maintain backward compatibility (for MINOR/PATCH versions)
4. Test in a WordPress environment if possible

### When Adding Features

1. Maintain the singleton pattern
2. Use WordPress hooks appropriately
3. Follow existing naming conventions
4. Add settings fields via `register_settings()` and `add_settings_field()`
5. Create corresponding sanitization in `sanitize_settings()`
6. Update version number according to Semantic Versioning
7. Document changes in CHANGELOG.md

### When Fixing Bugs

1. Identify the root cause
2. Ensure the fix doesn't break existing functionality
3. Update PATCH version number
4. Document the fix in CHANGELOG.md

## Common Tasks

### Adding a New Settings Field

1. Add field to `register_settings()` using `add_settings_field()`
2. Create render method for the field (e.g., `render_new_field()`)
3. Add sanitization in `sanitize_settings()`
4. Add corresponding getter method if needed
5. Update documentation

### Modifying the Media Uploader

The media uploader is implemented in `assets/js/admin.js` and uses:
- `wp.media()` for creating the uploader instance
- Localized strings via `councilControllerL10n` object
- jQuery for DOM manipulation

### Updating Styles

Admin styles are in `assets/css/admin.css` and are only loaded on the settings page. Follow WordPress admin styling conventions.

## Security Considerations for Agents

When making automated changes:

1. **Never remove security checks** (capability checks, nonces, sanitization)
2. **Always escape output** - If adding any echo statements, use appropriate escaping
3. **Validate user input** - Add sanitization for any new input fields
4. **Check for false returns** - WordPress functions like `wp_get_attachment_url()` can return false
5. **Use prepared statements** - If adding custom database queries (not currently used)

## Deployment

This plugin is designed as a Must-Use plugin:

1. Copy the entire directory to `/wp-content/mu-plugins/council-controller/`
2. Or copy `council-controller.php` and `assets/` folder to `/wp-content/mu-plugins/`
3. Plugin activates automatically
4. Access via **Dashboard → Council Settings**

## Useful Resources

- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Settings API](https://developer.wordpress.org/plugins/settings/settings-api/)
- [WordPress Media Upload](https://developer.wordpress.org/reference/functions/wp_enqueue_media/)
- [Semantic Versioning](https://semver.org/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

## Contact

For questions about this plugin's architecture or contributing, please refer to the CONTRIBUTING.md file or open an issue on the repository.
