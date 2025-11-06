# Contributing to Council Controller

Thank you for your interest in contributing to Council Controller! This document provides guidelines for contributing to this WordPress Must-Use plugin.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [How to Contribute](#how-to-contribute)
- [Coding Standards](#coding-standards)
- [Versioning](#versioning)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Enhancements](#suggesting-enhancements)

## Code of Conduct

Please be respectful and constructive in all interactions. We aim to maintain a welcoming environment for all contributors.

## Getting Started

### Prerequisites

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Basic understanding of WordPress plugin development
- Familiarity with WordPress Settings API

### Development Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/soundbyter/council-controller.git
   ```

2. Set up a local WordPress installation (use Local by Flywheel, XAMPP, or similar)

3. Copy the plugin to your WordPress mu-plugins directory:
   ```bash
   cp -r council-controller /path/to/wordpress/wp-content/mu-plugins/
   ```

4. The plugin will activate automatically (MU plugins don't require manual activation)

5. Navigate to Dashboard → Council Settings to test

## How to Contribute

### Types of Contributions

We welcome:
- Bug fixes
- Feature enhancements
- Documentation improvements
- Code optimization
- Translation files
- Security improvements

### Before You Start

1. Check existing [issues](https://github.com/soundbyter/council-controller/issues) and [pull requests](https://github.com/soundbyter/council-controller/pulls)
2. For major changes, open an issue first to discuss your proposed changes
3. For minor fixes, you can directly submit a pull request

## Coding Standards

### WordPress Coding Standards

This plugin follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).

#### PHP Standards

- Use WordPress naming conventions (lowercase with underscores)
- Follow WordPress indentation (tabs, not spaces)
- Add PHPDoc comments for all functions and classes
- Use WordPress functions when available (don't reinvent the wheel)

#### JavaScript Standards

- Use WordPress JavaScript coding standards
- Use jQuery for DOM manipulation
- Add JSDoc comments for functions

#### CSS Standards

- Follow WordPress CSS coding standards
- Use meaningful class names prefixed with `council-`
- Keep specificity low

### Security Requirements

**Critical**: All contributions must follow these security practices:

1. **Input Sanitization**
   - Use `sanitize_text_field()` for text inputs
   - Use `absint()` for integer values
   - Use `sanitize_email()` for email addresses
   - Use `sanitize_url()` for URLs

2. **Output Escaping**
   - Use `esc_html()` for HTML content
   - Use `esc_attr()` for HTML attributes
   - Use `esc_url()` for URLs
   - Use `wp_kses()` or `wp_kses_post()` for HTML with allowed tags

3. **Capability Checks**
   - Always check user capabilities before displaying admin content
   - Use `current_user_can( 'manage_options' )` for admin functions

4. **Nonces**
   - Use nonces for form submissions (Settings API handles this automatically)

5. **Direct Access Prevention**
   - All PHP files must include: `if ( ! defined( 'ABSPATH' ) ) { exit; }`

### Internationalization

All user-facing strings must be internationalized:

```php
// Good
__( 'Council Name', 'council-controller' );
esc_html__( 'Save Settings', 'council-controller' );

// Bad
echo 'Council Name';
```

## Versioning

This project adheres to [Semantic Versioning 2.0.0](https://semver.org/):

- **MAJOR** (X.0.0): Breaking changes, incompatible API changes
- **MINOR** (1.X.0): New features, backward compatible
- **PATCH** (1.0.X): Bug fixes, backward compatible

### When to Increment

- **MAJOR**: Changing function signatures, removing functions, WordPress version requirement increase
- **MINOR**: Adding new settings fields, new public API methods, new features
- **PATCH**: Bug fixes, security patches, documentation updates

### Version Update Process

When making changes that require a version bump:

1. Update version in `council-controller.php` header comment
2. Update `CHANGELOG.md` with your changes under appropriate version
3. Ensure version follows Semantic Versioning guidelines
4. Include version change in your commit message

## Pull Request Process

### Before Submitting

1. **Test your changes** in a local WordPress environment
2. **Check for errors** - Enable `WP_DEBUG` and ensure no PHP errors/warnings
3. **Validate HTML/CSS** if you've made frontend changes
4. **Review your code** - Check for console errors in browser developer tools
5. **Update documentation** if you've changed functionality
6. **Update CHANGELOG.md** with your changes

### PR Guidelines

1. **Branch naming**: Use descriptive names
   - Features: `feature/description`
   - Fixes: `fix/description`
   - Docs: `docs/description`

2. **Commit messages**: Write clear, descriptive commit messages
   ```
   Add council address field to settings page
   
   - Add new address field with textarea
   - Add sanitization for address field
   - Update settings page layout
   - Add getter method for address
   ```

3. **PR description**: Include
   - What changes you made
   - Why you made them
   - How to test them
   - Screenshots (if UI changes)
   - Related issue numbers

4. **Keep PRs focused**: One feature or fix per PR

5. **Code review**: Be responsive to feedback and questions

### PR Checklist

- [ ] Code follows WordPress coding standards
- [ ] All user inputs are sanitized
- [ ] All outputs are escaped
- [ ] Capability checks are in place
- [ ] Strings are internationalized
- [ ] Tested in WordPress environment
- [ ] No PHP errors/warnings with WP_DEBUG enabled
- [ ] Documentation updated if needed
- [ ] CHANGELOG.md updated
- [ ] Version number updated if needed (following Semantic Versioning)

## Reporting Bugs

### Before Reporting

1. Check if the bug has already been reported in [issues](https://github.com/soundbyter/council-controller/issues)
2. Test with default WordPress theme and no other plugins active
3. Test with latest version of WordPress and the plugin

### Bug Report Template

```markdown
**Describe the bug**
A clear description of what the bug is.

**To Reproduce**
Steps to reproduce:
1. Go to '...'
2. Click on '....'
3. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- WordPress Version: [e.g., 6.3]
- PHP Version: [e.g., 8.0]
- Plugin Version: [e.g., 1.0.0]
- Browser: [e.g., Chrome 118]

**Additional context**
Any other relevant information.
```

## Suggesting Enhancements

We welcome feature suggestions! Please:

1. Check if the enhancement has already been suggested
2. Open an issue with a clear description
3. Explain the use case and benefits
4. Include mockups or examples if applicable

### Enhancement Template

```markdown
**Feature Description**
Clear description of the proposed feature.

**Use Case**
Why this feature would be useful.

**Proposed Implementation**
How you think it could be implemented.

**Alternatives Considered**
Other approaches you've thought about.

**Additional Context**
Any other relevant information, mockups, examples, etc.
```

## Questions?

If you have questions about contributing:

1. Check the [AGENTS.md](AGENTS.md) file for technical details
2. Review existing [issues](https://github.com/soundbyter/council-controller/issues) and [pull requests](https://github.com/soundbyter/council-controller/pulls)
3. Open a new issue with the `question` label

## License

By contributing, you agree that your contributions will be licensed under the same license as the project.

---

Thank you for contributing to Council Controller! 🎉
