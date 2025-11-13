# Council Controller

A Must-Use WordPress plugin intended for use on a template parish or town council website, creating a settings page to fill out council information and serve it via shortcodes.

## Description

Council Controller provides a simple admin interface to manage your council's basic information, including:
- Council Name
- Council Logo (uploaded via WordPress Media Library)
- Color Scheme (Primary, Secondary, Tertiary, Heading, and Body colors)
- CSS Variables for use with page builders and themes

This information can be accessed programmatically and displayed on your website using shortcodes.

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
5. Configure your color scheme using the color pickers
6. Click "Save Settings"

### Color Management & CSS Variables

The plugin provides a color management system that outputs CSS variables for use with page builders and themes:

**Available CSS Variables:**
- `--council-primary` - Primary brand color
- `--council-secondary` - Secondary brand color
- `--council-tertiary` - Tertiary brand color
- `--council-h1` - H1 heading color
- `--council-h2` - H2 heading color
- `--council-h3` - H3 heading color
- `--council-h4` - H4 heading color
- `--council-h5` - H5 heading color
- `--council-h6` - H6 heading color
- `--council-link` - Link color
- `--council-menu-link` - Menu link color
- `--council-title-color` - Title color (for menu text when logo isn't available)
- `--council-body-text` - Default body text color
- `--council-button` - Button background color
- `--council-button-text` - Button text color
- `--council-button-hover` - Button hover background color
- `--council-button-text-hover` - Button text hover color

These variables can be used in your theme's CSS or page builder:

```css
.my-element {
    background-color: var(--council-primary);
    color: var(--council-body-text);
}

h1 {
    color: var(--council-h1);
}

h2 {
    color: var(--council-h2);
}

a {
    color: var(--council-link);
}

nav a, .menu a {
    color: var(--council-menu-link);
}

.site-title {
    color: var(--council-title-color);
}

button, .btn {
    background-color: var(--council-button);
    color: var(--council-button-text);
}

button:hover, .btn:hover {
    background-color: var(--council-button-hover);
    color: var(--council-button-text-hover);
}
```

### Using Shortcodes

The plugin provides four shortcodes to display council information on your website. **Complete documentation with examples is available within the WordPress admin interface on the Council Settings page.**

#### `[council_name]`
Displays the council name.

**Attributes:**
- `tag` - HTML tag to wrap the name: `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `p`, `span`, or `div` (default: `span`)
- `class` - Optional CSS class to add to the wrapper element
- `prepend` - Text to add before the council name
- `append` - Text to add after the council name

**Examples:**
```
[council_name]
[council_name tag="h1"]
[council_name tag="h2" class="my-council-name"]
[council_name tag="h1" prepend="Welcome to"]
[council_name prepend="Official Site of" tag="h2"]
[council_name tag="p" append="- Official Website"]
```

#### `[council_logo]`
Displays the council logo.

**Attributes:**
- `size` - Image size: `thumbnail`, `medium`, `large`, or `full` (default: `full`)
- `class` - Optional CSS class to add to the image
- `link` - Whether to link to the home page: `yes` or `no` (default: `no`)
- `aria_label` - ARIA label for accessibility. If not provided, uses the council name

**Examples:**
```
[council_logo]
[council_logo size="medium"]
[council_logo size="large" class="header-logo" link="yes"]
[council_logo aria_label="City Council Logo"]
[council_logo size="medium" aria_label="Official Council Emblem"]
```

#### `[council_info]`
Displays both council name and logo together in a formatted block.

**Attributes:**
- `logo_size` - Logo image size: `thumbnail`, `medium`, `large`, or `full` (default: `medium`)
- `show_name` - Show the name: `yes` or `no` (default: `yes`)
- `show_logo` - Show the logo: `yes` or `no` (default: `yes`)
- `name_tag` - HTML tag to wrap the name: `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `p`, `span`, or `div` (default: `h2`)
- `class` - Optional CSS class to add to the wrapper div
- `prepend` - Text to add before the council name
- `append` - Text to add after the council name

**Examples:**
```
[council_info]
[council_info logo_size="large"]
[council_info show_logo="no"]
[council_info name_tag="h1"]
[council_info logo_size="thumbnail" class="sidebar-council"]
[council_info name_tag="h1" prepend="Welcome to"]
[council_info prepend="Official Site of" logo_size="medium"]
```

#### `[council_hero_image]`
Returns the URL of the hero image with no HTML markup. Perfect for use in CSS background-image properties or PHP background styles.

**Attributes:**
- `size` - Image size: `thumbnail`, `medium`, `large`, or `full` (default: `full`)

**Examples:**
```
[council_hero_image]
[council_hero_image size="large"]
```

**Usage in PHP for background images:**
```php
<?php
// Get the hero image URL
$image_url = do_shortcode('[council_hero_image]');

// Clean it up just in case
$image_url = trim($image_url);

// Use as background style
if (!empty($image_url)) {
    echo '<div style="background-image: url(' . esc_url($image_url) . ');">';
    // Your content here
    echo '</div>';
}
?>
```

**Usage in custom CSS:**
```php
<?php if ($hero_url = do_shortcode('[council_hero_image size="full"]')): ?>
<style>
.hero-section {
    background-image: url('<?php echo esc_url($hero_url); ?>');
    background-size: cover;
    background-position: center;
}
</style>
<?php endif; ?>
```

#### `[council_hero_background]`
**New in v1.10.0:** Wraps content with a div that has the hero image as a full-width background. Perfect for use with page builder shortcode wrappers like Breakdance.

**Attributes:**
- `size` - Image size: `thumbnail`, `medium`, `large`, or `full` (default: `full`)
- `bg_size` - CSS background-size: `cover`, `contain`, `auto`, or custom like `"100% 100%"` (default: `cover`)
- `bg_repeat` - CSS background-repeat: `no-repeat`, `repeat`, `repeat-x`, `repeat-y` (default: `no-repeat`)
- `bg_position` - CSS background-position: `center`, `top`, `bottom`, `left`, `right`, or custom like `"50% 50%"` (default: `center`)
- `bg_attachment` - CSS background-attachment: `scroll`, `fixed`, `local` (default: `scroll`)
- `min_height` - Minimum height of the section (e.g., `"400px"`, `"50vh"`)
- `class` - Optional CSS class to add to the wrapper div

**Examples:**
```
[council_hero_background]Your content here[/council_hero_background]

[council_hero_background bg_size="cover" bg_position="center"]
  <h1>Welcome to our council</h1>
  <p>Some intro text</p>
[/council_hero_background]

[council_hero_background min_height="500px" bg_attachment="fixed"]
  Your hero content
[/council_hero_background]

[council_hero_background class="hero-section" bg_size="cover" bg_position="top center"]
  Page content with parallax-style background
[/council_hero_background]
```

**Usage in Breakdance:**
1. Add a "Shortcode Wrapper" element
2. Enter shortcode: `[council_hero_background bg_size="cover" min_height="600px"]`
3. Add your content inside the wrapper
4. The hero image will be applied as the full-width background

**Styling Tips:**
- Use `bg_attachment="fixed"` for parallax scrolling effect
- Combine with `min_height` to ensure visible background area
- Use `bg_position="top"` or `"bottom"` to control focal point
- Add `class` attribute for additional custom styling

#### `[parish_name]`
**New in v1.11.0:** Displays the parish name.

**Attributes:**
- `tag` - HTML tag to wrap the name: `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `p`, `span`, or `div` (default: `span`)
- `class` - Optional CSS class
- `prepend` - Text to add before the parish name
- `append` - Text to add after the parish name

**Examples:**
```
[parish_name]
[parish_name tag="h2"]
[parish_name tag="p" class="parish-heading"]
[parish_name tag="h1" prepend="Welcome to"]
```

#### `[parish_established_year]`
**New in v1.11.0:** Displays the year the parish was established.

**Attributes:**
- `tag` - HTML tag to wrap the year: `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `p`, `span`, or `div` (default: `span`)
- `class` - Optional CSS class
- `prepend` - Text to add before the year
- `append` - Text to add after the year

**Examples:**
```
[parish_established_year]
[parish_established_year tag="span" prepend="Est. "]
[parish_established_year tag="p" class="est-year"]
```

### Page Builder Integration via Custom Fields

**New in v1.9.0 (Fixed in v1.9.1):** Hero image and logo URLs are automatically added as custom fields to all pages and posts, making them accessible through page builder custom field/dynamic data features.

**Available Custom Fields:**
- `council_hero_image_url` - Hero image URL (full size)
- `council_logo_url` - Council logo URL (full size)
- `parish_name` - Parish name text
- `parish_established_year` - Parish established year
- `council_title_color` - Title color hex value

**Technical Note:** Custom fields are provided dynamically via WordPress metadata filters, ensuring they're always available on both frontend and backend, even before being written to the database.

**How to Use in Page Builders:**

*Elementor:*
1. Add a Dynamic Tags element
2. Select "Post" → "Custom Field"
3. Enter field name: `council_hero_image_url` or `council_logo_url`

*Beaver Builder:*
1. In any field, click the "+" icon
2. Select "Field Connections" → "Custom Field"
3. Enter field name: `council_hero_image_url` or `council_logo_url`

*Divi:*
1. In any module field, click the dynamic content icon
2. Select "Post Custom Field"
3. Enter field name: `council_hero_image_url` or `council_logo_url`

*Gutenberg:*
1. Use block bindings or custom field blocks
2. Reference field: `council_hero_image_url` or `council_logo_url`

**Example - Using in Background Image Field:**
- Field: Background Image URL
- Dynamic Data: Custom Field → `council_hero_image_url`
- The hero image will automatically be used as the background

**Benefits:**
- ✅ Native page builder integration
- ✅ No custom code required
- ✅ Fields update automatically when settings change
- ✅ Works with all major page builders
- ✅ Accessible through standard custom field features

### Accessing Settings Programmatically

You can also access the council settings in your theme or other plugins:

```php
// Get council name
$council_name = Council_Controller::get_council_name();

// Get council logo URL
$logo_url = Council_Controller::get_council_logo_url();

// Get council logo attachment ID
$logo_id = Council_Controller::get_council_logo_id();

// Get hero image URL (specify size: thumbnail, medium, large, full)
$hero_url = Council_Controller::get_hero_image_url('full');

// Get hero image attachment ID
$hero_id = Council_Controller::get_hero_image_id();
```

## REST API

**New in v1.14.0:** The plugin provides REST API endpoints for reading and updating all council settings programmatically. This is particularly useful for migrating old council websites to the template site.

**New in v1.15.0:** You can now upload images directly in the same API request using multipart/form-data, reducing the number of API calls needed.

### Authentication

- **GET requests** (reading data): No authentication required - data is public
- **POST/PUT requests** (updating data): Requires authentication with `manage_options` capability (typically Administrator role)

### Endpoints

#### Get All Settings

```
GET /wp-json/council-controller/v1/settings
```

**Response:**
```json
{
  "council_name": "Example Council",
  "parish_name": "Example Parish",
  "parish_established_year": "1850",
  "council_address": "123 Main Street\nTown, County\nPostcode",
  "meeting_venue_address": "Village Hall, High Street",
  "email_address": "clerk@example.gov.uk",
  "phone_number": "01234 567890",
  "clerk_name": "John Smith",
  "office_hours": "Monday-Friday 9am-5pm",
  "map_embed": "<iframe src='...'></iframe>",
  "meeting_schedule": "First Tuesday of every month",
  "annual_meeting_date": "May 15th",
  "county": "Example County",
  "council_logo": 123,
  "council_logo_url": "https://example.com/wp-content/uploads/2025/11/logo.png",
  "hero_image": 456,
  "hero_image_url": "https://example.com/wp-content/uploads/2025/11/hero.jpg",
  "primary_color": "#0066cc",
  "secondary_color": "#ff6600",
  "tertiary_color": "#00cc66",
  "h1_color": "#333333",
  "h2_color": "#444444",
  "h3_color": "#555555",
  "h4_color": "#666666",
  "h5_color": "#777777",
  "h6_color": "#888888",
  "link_color": "#0066cc",
  "menu_link_color": "#333333",
  "title_color": "#000000",
  "body_color": "#333333",
  "button_color": "#0066cc",
  "button_text_color": "#ffffff",
  "button_hover_color": "#0052a3",
  "button_text_hover_color": "#ffffff"
}
```

#### Update Settings

```
POST /wp-json/council-controller/v1/settings
PUT /wp-json/council-controller/v1/settings
```

**Authentication Required:** You must be logged in with `manage_options` capability.

**Request Body (JSON):**
```json
{
  "council_name": "New Council Name",
  "parish_name": "New Parish Name",
  "primary_color": "#ff0000",
  "council_logo": 789
}
```

**Or Upload Images Directly (Multipart/Form-Data, v1.15.0+):**
```bash
curl -X POST https://example.com/wp-json/council-controller/v1/settings \
  -u admin:password \
  -F "council_logo=@/path/to/logo.png" \
  -F "hero_image=@/path/to/hero.jpg" \
  -F "council_name=New Council Name"
```

**Notes:**
- You can update any combination of fields
- Only include fields you want to update
- Image fields accept either attachment IDs (JSON) or binary files (multipart)
- Supported image formats: JPEG, PNG, GIF, WebP, SVG (max 10MB each)
- Color fields must be valid hex colors (e.g., `#ff0000`) or empty strings
- Email addresses are validated for proper format

**Success Response:**
```json
{
  "success": true,
  "message": "Settings updated successfully.",
  "data": {
    // ... full settings object with updated values
  }
}
```

**Error Response Examples:**
```json
{
  "code": "invalid_email",
  "message": "Invalid email address format.",
  "data": { "status": 400 }
}
```

```json
{
  "code": "invalid_attachment",
  "message": "Invalid council logo attachment ID.",
  "data": { "status": 400 }
}
```

```json
{
  "code": "invalid_color",
  "message": "Invalid color format for primary_color. Must be a hex color.",
  "data": { "status": 400 }
}
```

### API Usage Examples

**Read Settings (cURL):**
```bash
curl https://example.com/wp-json/council-controller/v1/settings
```

**Update Settings (cURL with Authentication):**
```bash
curl -X POST https://example.com/wp-json/council-controller/v1/settings \
  -H "Content-Type: application/json" \
  -u admin:password \
  -d '{
    "council_name": "Updated Council Name",
    "primary_color": "#0066cc",
    "email_address": "clerk@newcouncil.gov.uk"
  }'
```

**Read Settings (JavaScript):**
```javascript
fetch('https://example.com/wp-json/council-controller/v1/settings')
  .then(response => response.json())
  .then(data => console.log(data));
```

**Update Settings (JavaScript with Authentication):**
```javascript
fetch('https://example.com/wp-json/council-controller/v1/settings', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Basic ' + btoa('admin:password')
  },
  body: JSON.stringify({
    council_name: 'Updated Council Name',
    primary_color: '#0066cc'
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

**Migration Script Example (PHP):**
```php
<?php
// Example script for migrating council data from old site to new template site

$old_site_data = array(
    'council_name'    => 'Example Town Council',
    'parish_name'     => 'Example Parish',
    'email_address'   => 'clerk@example.gov.uk',
    'phone_number'    => '01234 567890',
    'primary_color'   => '#0066cc',
    'secondary_color' => '#ff6600',
    // ... more fields
);

$new_site_url = 'https://newsite.com';
$username = 'admin';
$password = 'application_password'; // Use WordPress Application Password

$response = wp_remote_post( $new_site_url . '/wp-json/council-controller/v1/settings', array(
    'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password ),
    ),
    'body' => wp_json_encode( $old_site_data ),
) );

if ( is_wp_error( $response ) ) {
    echo 'Error: ' . $response->get_error_message();
} else {
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( isset( $body['success'] ) && $body['success'] ) {
        echo 'Successfully migrated council data!';
    } else {
        echo 'Migration failed: ' . print_r( $body, true );
    }
}
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Versioning

This plugin follows [Semantic Versioning 2.0.0](https://semver.org/). For the versions available, see the [CHANGELOG.md](CHANGELOG.md) file.

**Current Version:** 1.15.0

### Version Format

Given a version number MAJOR.MINOR.PATCH:
- **MAJOR** version changes when making incompatible API changes
- **MINOR** version changes when adding functionality in a backward compatible manner
- **PATCH** version changes when making backward compatible bug fixes

## Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

For technical details and guidelines for automated agents, see [AGENTS.md](AGENTS.md).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes for each version.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
