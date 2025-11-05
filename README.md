# Council Controller

A Must-Use WordPress plugin intended for use on a template parish or town council website, creating a settings page to fill out council information and serve it via shortcodes.

## Description

Council Controller provides a simple admin interface to manage your council's basic information, including:
- Council Name
- Council Logo (uploaded via WordPress Media Library)

This information can be accessed programmatically and used in shortcodes (future functionality).

## Installation

As a Must-Use (MU) plugin, this plugin should be installed in your WordPress `mu-plugins` directory:

1. Copy the entire `council-controller` directory to `/wp-content/mu-plugins/`
2. Alternatively, copy just the `council-controller.php` file and `assets` folder directly to `/wp-content/mu-plugins/`

The plugin will be automatically activated - no need to activate it through the WordPress admin.

## Usage

### Admin Interface

1. Log in to your WordPress admin dashboard
2. Navigate to **Council Settings** in the admin menu
3. Enter your council name
4. Click "Choose Logo" to select or upload a logo from the media library
5. Click "Save Settings"

### Accessing Settings Programmatically

You can access the council settings in your theme or other plugins:

```php
// Get council name
$council_name = Council_Controller::get_council_name();

// Get council logo URL
$logo_url = Council_Controller::get_council_logo_url();

// Get council logo attachment ID
$logo_id = Council_Controller::get_council_logo_id();
```

## Features

- **WordPress Settings API Integration**: Proper settings management using WordPress best practices
- **Media Library Integration**: Easy logo selection using the native WordPress media uploader
- **Sanitization & Security**: All inputs are properly sanitized and escaped
- **Translation Ready**: All strings are internationalized and ready for translation
- **Clean UI**: Simple, intuitive admin interface following WordPress design patterns

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Versioning

This plugin follows [Semantic Versioning 2.0.0](https://semver.org/). For the versions available, see the [CHANGELOG.md](CHANGELOG.md) file.

**Current Version:** 1.0.0

### Version Format

Given a version number MAJOR.MINOR.PATCH:
- **MAJOR** version changes when making incompatible API changes
- **MINOR** version changes when adding functionality in a backward compatible manner
- **PATCH** version changes when making backward compatible bug fixes

## Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

For technical details and guidelines for automated agents, see [AGENTS.md](AGENTS.md).

## Future Enhancements

- Shortcodes to display council information on the front-end
- Additional fields (address, phone, email, etc.)
- Multiple logo sizes/variations
- Social media links

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes for each version.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
