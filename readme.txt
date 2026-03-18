=== MCP Adapter Readonly Abilities ===
Contributors: mukitkhan
Tags: mcp, ai, content, readonly, api
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Registers read-only abilities for WordPress content via MCP. AI clients like Claude can access posts, pages, comments, and media.

== Description ==

MCP Adapter Readonly Abilities extends WordPress with read-only content abilities for the Model Context Protocol (MCP). Once installed with MCP Adapter, AI clients can securely access your published content.

**Features:**

* Get all published posts with full content
* Get single post by ID
* Get all published pages
* Get all categories
* Get all tags
* Get approved comments
* Get media attachments
* Search posts by keyword
* Pagination support
* Filter media by mime type

**Available Abilities:**

* `reader/get-posts` - Returns all published posts with pagination and search
* `reader/get-post` - Returns a single post by ID
* `reader/get-pages` - Returns all published pages
* `reader/get-categories` - Returns all categories
* `reader/get-tags` - Returns all tags
* `reader/get-comments` - Returns approved comments (optionally filter by post)
* `reader/get-media` - Returns media attachments (optionally filter by mime type)
* `reader/get-media-item` - Returns a single media item by ID

**Requirements:**

* WordPress 6.9 or higher
* MCP Adapter plugin installed and configured

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure MCP Adapter is installed and configured
4. Configure your AI client to connect via MCP

== Setup with Claude Desktop ==

Follow these steps to connect Claude Desktop to your WordPress site:

**Step 1: Install Required Plugins**

Install and activate on your WordPress site:
1. MCP Adapter plugin
2. MCP Adapter Readonly Abilities (this plugin)

**Step 2: Get Your MCP Endpoint**

Your MCP endpoint URL will be:
`https://your-site.com/wp-json/mcp/v1`

**Step 3: Configure Claude Desktop**

Open Claude Desktop configuration file:

* **macOS:** `~/Library/Application Support/Claude/claude_desktop_config.json`
* **Windows:** `%APPDATA%\Claude\claude_desktop_config.json`

Add your WordPress MCP server:

`{
  "mcpServers": {
    "wordpress": {
      "url": "https://your-site.com/wp-json/mcp/v1"
    }
  }
}`

**Step 4: Restart Claude Desktop**

Restart Claude Desktop to load the new configuration.

**Step 5: Test the Connection**

Ask Claude to read your WordPress content:

* "List all posts from my WordPress site"
* "Get the post with ID 123"
* "Show me all categories"
* "Get comments for post ID 45"
* "List all images from my media library"

== Example Prompts for Claude ==

Once connected, try these prompts:

**Posts:**
* "Get my latest 10 blog posts"
* "Search for posts about 'tutorial'"
* "Show me the full content of post ID 42"

**Pages:**
* "List all pages on my site"

**Taxonomies:**
* "What categories do I have?"
* "Show all tags"

**Comments:**
* "Get recent comments"
* "Show comments on post ID 15"

**Media:**
* "List all images in my media library"
* "Get details for media ID 100"
* "Show all PDF files"

== Ability Parameters ==

**reader/get-posts**
* `per_page` (int): Number of posts, default 20, max 100
* `page` (int): Page number, default 1
* `search` (string): Search keyword

**reader/get-post**
* `id` (int, required): Post ID

**reader/get-pages**
* `per_page` (int): Number of pages, default 20

**reader/get-comments**
* `post_id` (int): Filter by post ID
* `per_page` (int): Number of comments, default 20, max 100

**reader/get-media**
* `per_page` (int): Number of items, default 20, max 100
* `mime_type` (string): Filter by type (e.g., "image", "video", "application/pdf")

**reader/get-media-item**
* `id` (int, required): Media ID

== Frequently Asked Questions ==

= What is MCP? =

MCP (Model Context Protocol) is a protocol that allows AI assistants to interact with external systems securely.

= Is my content safe? =

Yes. This plugin only provides read-only access to published content. No modifications can be made through these abilities. Draft, private, and password-protected content is not exposed.

= Do I need MCP Adapter? =

Yes. This plugin registers abilities that work with the MCP Adapter plugin.

= What content is accessible? =

Only published posts, published pages, approved comments, and public media attachments are accessible.

= Can AI modify my content? =

No. All abilities are strictly read-only. The `readonly` annotation ensures no write operations are possible.

== Screenshots ==

1. Plugin activated in WordPress
2. Claude Desktop reading WordPress posts
3. MCP configuration example

== Changelog ==

= 1.0.0 =
* Initial release
* Added get-posts ability with pagination and search
* Added get-post ability for single post retrieval
* Added get-pages ability
* Added get-categories ability
* Added get-tags ability
* Added get-comments ability with post filtering
* Added get-media ability with mime type filtering
* Added get-media-item ability for single media details

== Upgrade Notice ==

= 1.0.0 =
Initial release with full read-only access to posts, pages, categories, tags, comments, and media.
