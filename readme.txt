=== MCP Adapter Readonly Abilities Plugin ===
Contributors: mukitkhan
Tags: mcp, api, content, readonly, ai
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Registers read-only abilities for WordPress content via MCP (Model Context Protocol). Allows AI clients like Claude to access your posts, pages, categories, and tags.

== Description ==

MCP Adapter Readonly Abilities Plugin extends WordPress with read-only content abilities for the Model Context Protocol (MCP). Once installed with MCP Adapter, AI clients can securely access your published content.

**Features:**

* Get all published posts with full content
* Get single post by ID
* Get all published pages
* Get all categories
* Get all tags
* Search posts by keyword
* Pagination support

**Available Abilities:**

* `reader/get-posts` - Returns all published posts
* `reader/get-post` - Returns a single post by ID
* `reader/get-pages` - Returns all published pages
* `reader/get-categories` - Returns all categories
* `reader/get-tags` - Returns all tags

**Requirements:**

* WordPress 6.9 or higher
* MCP Adapter plugin installed

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure MCP Adapter is installed and configured
4. AI clients can now access your content via MCP

== Frequently Asked Questions ==

= What is MCP? =

MCP (Model Context Protocol) is a protocol that allows AI assistants to interact with external systems securely.

= Is my content safe? =

Yes. This plugin only provides read-only access to published content. No modifications can be made through these abilities.

= Do I need MCP Adapter? =

Yes. This plugin registers abilities that work with the MCP Adapter plugin.

== Changelog ==

= 1.0.0 =
* Initial release
* Added get-posts ability with pagination and search
* Added get-post ability for single post retrieval
* Added get-pages ability
* Added get-categories ability
* Added get-tags ability

== Upgrade Notice ==

= 1.0.0 =
Initial release.
