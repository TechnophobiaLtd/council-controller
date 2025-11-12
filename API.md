# Council Controller REST API Documentation

Version 1.14.0

## Overview

The Council Controller plugin provides REST API endpoints for programmatic access to all council settings. This is particularly useful for:
- Migrating council data from old websites to new template sites
- Integrating council information with external systems
- Automated backup and restore operations
- Bulk updates across multiple council websites

## Base URL

```
https://your-wordpress-site.com/wp-json/council-controller/v1
```

## Authentication

### GET Requests (Read)
No authentication required. All council data is publicly accessible.

### POST/PUT Requests (Write)
Requires WordPress authentication with `manage_options` capability (typically Administrator role).

**Authentication Methods:**
1. **WordPress Session Cookie** - When logged into WordPress admin
2. **Basic Authentication** - Username and password
3. **Application Passwords** - Recommended for external integrations (WordPress 5.6+)

## Endpoints

### Get All Settings

Retrieve all council settings including text fields, images, and colors.

**Endpoint:** `GET /settings`

**Authentication:** None required

**Response:** `200 OK`

```json
{
  "council_name": "Example Town Council",
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

### Update Settings

Update any combination of council settings.

**Endpoint:** `POST /settings` or `PUT /settings`

**Authentication:** Required (`manage_options` capability)

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "council_name": "Updated Council Name",
  "parish_name": "Updated Parish",
  "primary_color": "#ff0000",
  "council_logo": 789,
  "email_address": "newclerk@example.gov.uk"
}
```

**Notes:**
- Only include fields you want to update
- Omitted fields will retain their current values
- All fields are optional

**Success Response:** `200 OK`

```json
{
  "success": true,
  "message": "Settings updated successfully.",
  "data": {
    // ... complete settings object with updated values
  }
}
```

**Error Responses:**

`400 Bad Request` - Validation error
```json
{
  "code": "invalid_email",
  "message": "Invalid email address format.",
  "data": {
    "status": 400
  }
}
```

`401 Unauthorized` - Not authenticated
```json
{
  "code": "rest_forbidden",
  "message": "Sorry, you are not allowed to do that.",
  "data": {
    "status": 401
  }
}
```

`403 Forbidden` - Insufficient permissions
```json
{
  "code": "rest_forbidden",
  "message": "Sorry, you are not allowed to do that.",
  "data": {
    "status": 403
  }
}
```

`500 Internal Server Error` - Update failed
```json
{
  "code": "update_failed",
  "message": "Failed to update settings.",
  "data": {
    "status": 500
  }
}
```

## Field Reference

### Text Fields

| Field | Type | Description | Validation |
|-------|------|-------------|------------|
| `council_name` | string | Council name | Plain text |
| `parish_name` | string | Parish name | Plain text |
| `parish_established_year` | string | Year parish was established | Plain text |
| `council_address` | string | Council office address | Textarea (supports line breaks) |
| `meeting_venue_address` | string | Meeting venue address | Textarea (supports line breaks) |
| `email_address` | string | Council email address | Valid email format |
| `phone_number` | string | Contact phone number | Plain text |
| `clerk_name` | string | Clerk's name | Plain text |
| `office_hours` | string | Office opening hours | Textarea (supports line breaks) |
| `map_embed` | string | Map embed code or coordinates | HTML (iframes allowed) |
| `meeting_schedule` | string | Regular meeting schedule | Plain text |
| `annual_meeting_date` | string | Annual meeting date | Plain text |
| `county` | string | County location | Plain text |

### Image Fields

| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `council_logo` | integer | Logo attachment ID | Must be valid WordPress media attachment |
| `council_logo_url` | string | Logo URL (read-only) | Automatically generated from attachment ID |
| `hero_image` | integer | Hero image attachment ID | Must be valid WordPress media attachment |
| `hero_image_url` | string | Hero image URL (read-only) | Automatically generated from attachment ID |

**Note:** Image URLs are provided for convenience in GET requests but cannot be set directly. To update images, provide the WordPress attachment ID. Use `0` to clear an image.

### Color Fields

All color fields must be valid hex colors (e.g., `#ff0000` or `#f00`) or empty strings.

| Field | Description | CSS Variable |
|-------|-------------|--------------|
| `primary_color` | Primary brand color | `--council-primary` |
| `secondary_color` | Secondary brand color | `--council-secondary` |
| `tertiary_color` | Tertiary brand color | `--council-tertiary` |
| `h1_color` | H1 heading color | `--council-h1` |
| `h2_color` | H2 heading color | `--council-h2` |
| `h3_color` | H3 heading color | `--council-h3` |
| `h4_color` | H4 heading color | `--council-h4` |
| `h5_color` | H5 heading color | `--council-h5` |
| `h6_color` | H6 heading color | `--council-h6` |
| `link_color` | Link color | `--council-link` |
| `menu_link_color` | Menu link color | `--council-menu-link` |
| `title_color` | Title color | `--council-title-color` |
| `body_color` | Body text color | `--council-body-text` |
| `button_color` | Button background | `--council-button` |
| `button_text_color` | Button text | `--council-button-text` |
| `button_hover_color` | Button hover background | `--council-button-hover` |
| `button_text_hover_color` | Button hover text | `--council-button-text-hover` |

## Usage Examples

### cURL Examples

**Get Settings:**
```bash
curl https://example.com/wp-json/council-controller/v1/settings
```

**Update Settings with Basic Auth:**
```bash
curl -X POST https://example.com/wp-json/council-controller/v1/settings \
  -H "Content-Type: application/json" \
  -u admin:password \
  -d '{
    "council_name": "Updated Council",
    "primary_color": "#0066cc"
  }'
```

**Update Settings with Application Password:**
```bash
curl -X POST https://example.com/wp-json/council-controller/v1/settings \
  -H "Content-Type: application/json" \
  -u username:application_password \
  -d '{
    "email_address": "clerk@newcouncil.gov.uk",
    "phone_number": "01234 567890"
  }'
```

### JavaScript Examples

**Fetch Settings:**
```javascript
fetch('https://example.com/wp-json/council-controller/v1/settings')
  .then(response => response.json())
  .then(data => {
    console.log('Council Name:', data.council_name);
    console.log('Primary Color:', data.primary_color);
  })
  .catch(error => console.error('Error:', error));
```

**Update Settings:**
```javascript
const updateData = {
  council_name: 'New Council Name',
  primary_color: '#ff0000',
  email_address: 'clerk@example.gov.uk'
};

fetch('https://example.com/wp-json/council-controller/v1/settings', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Basic ' + btoa('username:password')
  },
  body: JSON.stringify(updateData)
})
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Settings updated!');
    } else {
      console.error('Update failed:', data.message);
    }
  })
  .catch(error => console.error('Error:', error));
```

### PHP Examples

**Get Settings:**
```php
<?php
$response = wp_remote_get( 'https://example.com/wp-json/council-controller/v1/settings' );

if ( is_wp_error( $response ) ) {
    echo 'Error: ' . $response->get_error_message();
} else {
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    echo 'Council Name: ' . $body['council_name'];
}
```

**Update Settings:**
```php
<?php
$update_data = array(
    'council_name'  => 'Updated Council',
    'primary_color' => '#0066cc',
    'email_address' => 'clerk@example.gov.uk',
);

$response = wp_remote_post( 
    'https://example.com/wp-json/council-controller/v1/settings',
    array(
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( 'username:password' ),
        ),
        'body' => wp_json_encode( $update_data ),
    )
);

if ( is_wp_error( $response ) ) {
    echo 'Error: ' . $response->get_error_message();
} else {
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( isset( $body['success'] ) && $body['success'] ) {
        echo 'Settings updated successfully!';
    } else {
        echo 'Update failed: ' . ( $body['message'] ?? 'Unknown error' );
    }
}
```

### Python Example

```python
import requests
import json

# Get settings
response = requests.get('https://example.com/wp-json/council-controller/v1/settings')
data = response.json()
print(f"Council Name: {data['council_name']}")

# Update settings
update_data = {
    'council_name': 'Updated Council',
    'primary_color': '#0066cc',
    'email_address': 'clerk@example.gov.uk'
}

response = requests.post(
    'https://example.com/wp-json/council-controller/v1/settings',
    auth=('username', 'password'),
    json=update_data
)

result = response.json()
if result.get('success'):
    print('Settings updated successfully!')
else:
    print(f"Update failed: {result.get('message')}")
```

## Migration Workflow

Here's a complete example of migrating council data from an old site to a new template site:

```php
<?php
/**
 * Council Data Migration Script
 * 
 * This script reads council information from an old website
 * and transfers it to a new template site via the REST API.
 */

// Old site configuration (source)
$old_site_data = array(
    'council_name'            => 'Oldtown Parish Council',
    'parish_name'             => 'Oldtown',
    'parish_established_year' => '1894',
    'council_address'         => "Parish Office\n123 High Street\nOldtown\nST1 2AB",
    'email_address'           => 'clerk@oldtown-pc.gov.uk',
    'phone_number'            => '01234 567890',
    'clerk_name'              => 'Mrs Jane Smith',
    'office_hours'            => "Monday: 9am-12pm\nWednesday: 2pm-5pm",
    'meeting_schedule'        => 'Second Tuesday of each month at 7pm',
    'county'                  => 'Staffordshire',
    'primary_color'           => '#2c5f8d',
    'secondary_color'         => '#8db356',
    // ... add all other fields
);

// New site configuration (destination)
$new_site_url = 'https://newtemplate.council.gov.uk';
$api_endpoint = $new_site_url . '/wp-json/council-controller/v1/settings';
$username = 'admin';
$app_password = 'your-application-password-here';

// Perform the migration
echo "Starting migration...\n";

$response = wp_remote_post( $api_endpoint, array(
    'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $app_password ),
    ),
    'body'    => wp_json_encode( $old_site_data ),
    'timeout' => 30,
) );

// Handle the response
if ( is_wp_error( $response ) ) {
    echo "Error: " . $response->get_error_message() . "\n";
    exit( 1 );
}

$status_code = wp_remote_retrieve_response_code( $response );
$body = json_decode( wp_remote_retrieve_body( $response ), true );

if ( $status_code === 200 && isset( $body['success'] ) && $body['success'] ) {
    echo "✓ Migration completed successfully!\n";
    echo "  Council Name: " . $body['data']['council_name'] . "\n";
    echo "  Primary Color: " . $body['data']['primary_color'] . "\n";
} else {
    echo "✗ Migration failed!\n";
    echo "  Status Code: " . $status_code . "\n";
    echo "  Message: " . ( $body['message'] ?? 'Unknown error' ) . "\n";
    if ( isset( $body['code'] ) ) {
        echo "  Error Code: " . $body['code'] . "\n";
    }
    exit( 1 );
}
```

## Error Handling

Always check for errors when making API requests:

1. **Network errors** - Connection failures, timeouts
2. **Authentication errors** - Invalid credentials (401, 403)
3. **Validation errors** - Invalid field values (400)
4. **Server errors** - Internal failures (500)

Best practice is to:
- Check HTTP status codes
- Parse error responses
- Log errors for debugging
- Provide meaningful user feedback
- Implement retry logic for transient failures

## Rate Limiting

The WordPress REST API has built-in rate limiting. For bulk operations:
- Add delays between requests
- Use batch processing for large datasets
- Monitor server resources
- Consider off-peak hours for large migrations

## Security Best Practices

1. **Use Application Passwords** instead of main account passwords
2. **Use HTTPS** for all API requests to encrypt credentials
3. **Rotate credentials** regularly
4. **Limit permissions** - only grant `manage_options` to necessary accounts
5. **Log API access** for audit trails
6. **Validate responses** before using the data
7. **Sanitize inputs** even when reading from trusted sources

## Support

For issues or questions:
- Review plugin documentation: README.md
- Check changelog: CHANGELOG.md
- Review code: council-controller.php
- Submit issues to the GitHub repository

## Version

This documentation is for Council Controller version 1.14.0.
