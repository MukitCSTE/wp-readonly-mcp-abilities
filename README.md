# WP Readonly MCP Abilities

A WordPress plugin that registers read-only abilities for posts, pages, categories, and tags via the Model Context Protocol (MCP). Once installed with the MCP Adapter, AI clients like Claude can access your WordPress content.

## Requirements

- WordPress 6.9 or higher
- PHP 7.4 or higher
- [WordPress MCP Adapter](https://github.com/WordPress/mcp-adapter) plugin

## Installation

1. Download the plugin files
2. Upload the `wp-readonly-mcp-abilities` folder to `/wp-content/plugins/`
3. Activate the plugin through the WordPress admin panel
4. Ensure the MCP Adapter plugin is also installed and activated

**Important:** Activate plugins in this order:
1. First: MCP Adapter
2. Then: WP Readonly MCP Abilities

## Available Abilities

| Ability | Description |
|---------|-------------|
| `reader/get-posts` | Get all published posts with pagination and search |
| `reader/get-post` | Get a single post by ID |
| `reader/get-pages` | Get all published pages |
| `reader/get-categories` | Get all categories |
| `reader/get-tags` | Get all tags |

## Claude Desktop Configuration

### Local Development (localhost)

```json
{
  "mcpServers": {
    "wordpress": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "http://localhost:8081/index.php?rest_route=/mcp/mcp-adapter-default-server",
        "WP_API_USERNAME": "your-username",
        "WP_API_PASSWORD": "your-application-password"
      }
    }
  }
}
```

### Production (HTTPS)

```json
{
  "mcpServers": {
    "wordpress": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "https://your-site.com/wp-json/mcp/mcp-adapter-default-server",
        "WP_API_USERNAME": "your-username",
        "WP_API_PASSWORD": "your-application-password"
      }
    }
  }
}
```

## WordPress User Setup

1. Create a WordPress user with **Subscriber** role (read-only)
2. Generate an Application Password for that user:
   - Go to Users > Profile
   - Scroll to "Application Passwords"
   - Enter a name (e.g., "Claude MCP")
   - Click "Add New Application Password"
   - Copy the generated password (spaces are fine)

## Production Deployment

### Prerequisites

- WordPress 6.9+ with HTTPS enabled
- Admin access to install plugins
- MCP Adapter plugin installed

### Steps

1. **Install MCP Adapter:**
   - Download from [WordPress/mcp-adapter](https://github.com/WordPress/mcp-adapter)
   - Upload via Plugins > Add New > Upload Plugin
   - Activate the plugin

2. **Install WP Readonly MCP Abilities:**
   - Upload this plugin to `/wp-content/plugins/`
   - Activate via Plugins menu

3. **Create MCP User:**
   - Go to Users > Add New
   - Create user with **Subscriber** role
   - Generate Application Password

4. **Update Claude Desktop config:**
   - Use your production URL with `/wp-json/` path
   - Use the new user credentials

### wp-config.php Settings (if needed)

For local development without HTTPS:

```php
define( 'WP_ENVIRONMENT_TYPE', 'local' );
```

For dynamic URL detection (useful for multiple environments):

```php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('WP_HOME', $protocol . $host);
define('WP_SITEURL', $protocol . $host);
```

## Security Notes

- This plugin only exposes **read-only** abilities
- All abilities have `'annotations' => array( 'readonly' => true )`
- Only published content is accessible
- No create, update, or delete operations
- Use Subscriber role for MCP user to minimize permissions

## License

GPL-2.0-or-later

## Author

Md Mukit Khan <mukitkhan07@gmail.com>
