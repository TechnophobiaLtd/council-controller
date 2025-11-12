# Council Controller REST API - Quick Reference

## Endpoints

### Get All Settings (Public)
```
GET /wp-json/council-controller/v1/settings
```
No authentication required.

### Update Settings (Authenticated)
```
POST /wp-json/council-controller/v1/settings
PUT /wp-json/council-controller/v1/settings
```
Requires `manage_options` capability (Administrator).

## Authentication

Use one of:
- WordPress session cookie (when logged in)
- HTTP Basic Auth: `username:password`
- Application Password (recommended): `username:app_password`

## Fields (32 Total)

### Text Fields (13)
- `council_name`, `parish_name`, `parish_established_year`
- `council_address`, `meeting_venue_address`
- `email_address`, `phone_number`, `clerk_name`
- `office_hours`, `map_embed`
- `meeting_schedule`, `annual_meeting_date`, `county`

### Image Fields (2 + URLs)
- `council_logo` (ID), `council_logo_url` (URL, read-only)
- `hero_image` (ID), `hero_image_url` (URL, read-only)

### Color Fields (17)
- `primary_color`, `secondary_color`, `tertiary_color`
- `h1_color`, `h2_color`, `h3_color`, `h4_color`, `h5_color`, `h6_color`
- `link_color`, `menu_link_color`, `title_color`, `body_color`
- `button_color`, `button_text_color`, `button_hover_color`, `button_text_hover_color`

## Quick Examples

### cURL - Get Settings
```bash
curl https://example.com/wp-json/council-controller/v1/settings
```

### cURL - Update Settings
```bash
curl -X POST https://example.com/wp-json/council-controller/v1/settings \
  -u admin:password \
  -H "Content-Type: application/json" \
  -d '{"council_name":"New Name","primary_color":"#0066cc"}'
```

### JavaScript - Fetch
```javascript
fetch('https://example.com/wp-json/council-controller/v1/settings')
  .then(res => res.json())
  .then(data => console.log(data.council_name));
```

### JavaScript - Update
```javascript
fetch('https://example.com/wp-json/council-controller/v1/settings', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Basic ' + btoa('user:pass')
  },
  body: JSON.stringify({ council_name: 'New Name' })
}).then(res => res.json()).then(data => console.log(data));
```

### PHP - Get
```php
$response = wp_remote_get('https://example.com/wp-json/council-controller/v1/settings');
$data = json_decode(wp_remote_retrieve_body($response), true);
```

### PHP - Update
```php
wp_remote_post('https://example.com/wp-json/council-controller/v1/settings', [
    'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode('user:pass'),
    ],
    'body' => wp_json_encode(['council_name' => 'New Name']),
]);
```

## Validation Rules

| Field Type | Validation |
|------------|------------|
| Text | Plain text, sanitized |
| Email | Must be valid email format |
| Color | Must be hex color (#fff or #ffffff) |
| Image | Must be valid WordPress attachment ID |

## Error Codes

- `400` - Bad Request (validation error)
- `401` - Unauthorized (not authenticated)
- `403` - Forbidden (insufficient permissions)
- `500` - Internal Server Error (update failed)

## Response Format

### Success
```json
{
  "success": true,
  "message": "Settings updated successfully.",
  "data": { /* all settings */ }
}
```

### Error
```json
{
  "code": "invalid_email",
  "message": "Invalid email address format.",
  "data": { "status": 400 }
}
```

## Migration Workflow

1. **Get settings from old site**
   ```bash
   curl https://old-site.com/wp-json/council-controller/v1/settings > data.json
   ```

2. **Review and edit data.json if needed**

3. **Upload to new site**
   ```bash
   curl -X POST https://new-site.com/wp-json/council-controller/v1/settings \
     -u admin:password \
     -H "Content-Type: application/json" \
     -d @data.json
   ```

## Tips

- ✅ Only include fields you want to update
- ✅ Use Application Passwords for security
- ✅ Always use HTTPS
- ✅ Empty strings clear field values
- ✅ Image URLs are read-only, update IDs instead
- ✅ Color validation is strict (hex format only)

## Documentation

- Full API docs: `API.md`
- Plugin docs: `README.md`
- Changelog: `CHANGELOG.md`

---
Council Controller v1.14.0
