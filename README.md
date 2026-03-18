# MCP Adapter Readonly Abilities

A WordPress plugin that registers read-only abilities for posts, pages, categories, tags, comments, and media via the Model Context Protocol (MCP). Once installed with the MCP Adapter, AI clients like Claude can access your WordPress content.

## Download

**Latest Release:** [v1.0.0](https://github.com/MukitCSTE/wp-readonly-mcp-abilities/releases/tag/v1.0.0)

[Download mcp-adapter-readonly-abilities-1.0.0.zip](https://github.com/MukitCSTE/wp-readonly-mcp-abilities/releases/download/v1.0.0/mcp-adapter-readonly-abilities-1.0.0.zip)

## Requirements

- WordPress 6.9 or higher
- PHP 7.4 or higher
- [WordPress MCP Adapter](https://github.com/WordPress/mcp-adapter) plugin

## Installation

1. Download the [latest release](https://github.com/MukitCSTE/wp-readonly-mcp-abilities/releases/latest)
2. Upload the zip via Plugins > Add New > Upload Plugin
3. Activate the plugin through the WordPress admin panel
4. Ensure the MCP Adapter plugin is also installed and activated

**Important:** Activate plugins in this order:
1. First: MCP Adapter
2. Then: MCP Adapter Readonly Abilities

## Available Abilities

| Ability | Description |
|---------|-------------|
| `reader/get-posts` | Get all published posts with pagination and search |
| `reader/get-post` | Get a single post by ID |
| `reader/get-pages` | Get all published pages |
| `reader/get-categories` | Get all categories |
| `reader/get-tags` | Get all tags |
| `reader/get-comments` | Get approved comments (filter by post) |
| `reader/get-media` | Get media attachments (filter by mime type) |
| `reader/get-media-item` | Get a single media item by ID |

## Ability Parameters

### reader/get-posts
- `per_page` (int): Number of posts, default 20, max 100
- `page` (int): Page number, default 1
- `search` (string): Search keyword

### reader/get-post
- `id` (int, required): Post ID

### reader/get-pages
- `per_page` (int): Number of pages, default 20

### reader/get-comments
- `post_id` (int): Filter by post ID
- `per_page` (int): Number of comments, default 20, max 100

### reader/get-media
- `per_page` (int): Number of items, default 20, max 100
- `mime_type` (string): Filter by type (e.g., "image", "video", "application/pdf")

### reader/get-media-item
- `id` (int, required): Media ID

## Claude Desktop Configuration

### Local Development (localhost)

```json
{
  "mcpServers": {
    "wordpress": {
      "command": "npx",
      "args": ["-y", "@anthropic-ai/mcp-remote@latest", "http://localhost:8081/index.php?rest_route=/mcp/mcp-adapter-default-server/sse"],
      "env": {
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
      "args": ["-y", "@anthropic-ai/mcp-remote@latest", "https://your-site.com/wp-json/mcp/mcp-adapter-default-server/sse"],
      "env": {
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

## Example Prompts for Claude

Once connected, try these prompts:

**Posts:**
- "Get my latest 10 blog posts"
- "Search for posts about 'tutorial'"
- "Show me the full content of post ID 42"

**Pages:**
- "List all pages on my site"

**Comments:**
- "Get recent comments"
- "Show comments on post ID 15"

**Media:**
- "List all images in my media library"
- "Get details for media ID 100"
- "Show all PDF files"

## Security Notes

- This plugin only exposes **read-only** abilities
- All abilities have `'annotations' => array( 'readonly' => true )`
- Only published content is accessible
- No create, update, or delete operations
- Use Subscriber role for MCP user to minimize permissions

## License

GPL-2.0-or-later

## Author

Md Mukit Khan - [GitHub](https://github.com/MukitCSTE)
