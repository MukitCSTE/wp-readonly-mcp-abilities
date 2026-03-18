<?php
/**
 * Plugin Name: MCP Adapter Readonly Abilities Plugin
 * Plugin URI: https://github.com/MukitCSTE/wp-readonly-mcp-abilities
 * Description: Registers read-only abilities for WordPress posts, pages, categories, and tags via MCP (Model Context Protocol). Once installed with MCP Adapter, AI clients like Claude can access your content.
 * Version: 1.0.0
 * Requires at least: 6.9
 * Requires PHP: 7.4
 * Author: Md Mukit Khan
 * Author URI: mailto:mukitkhan07@gmail.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mcp-adapter-readonly-abilities
 *
 * @package MCP_Adapter_Readonly_Abilities
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register the ability category for content reading.
 */
add_action( 'wp_abilities_api_categories_init', 'mcp_reader_register_category' );

function mcp_reader_register_category() {
    wp_register_ability_category(
        'reader',
        array(
            'label'       => __( 'Content Reader', 'mcp-adapter-readonly-abilities' ),
            'description' => __( 'Read-only abilities for WordPress content via MCP.', 'mcp-adapter-readonly-abilities' ),
        )
    );
}

/**
 * Register all read-only abilities.
 */
add_action( 'wp_abilities_api_init', 'mcp_reader_register_abilities' );

function mcp_reader_register_abilities() {

    // Get all posts with full content
    wp_register_ability(
        'reader/get-posts',
        array(
            'category'    => 'reader',
            'label'       => __( 'Get All Posts', 'mcp-adapter-readonly-abilities' ),
            'description' => __( 'Returns all published posts with full content.', 'mcp-adapter-readonly-abilities' ),
            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'per_page' => array(
                        'type'        => 'integer',
                        'description' => 'Number of posts (default 20, max 100)',
                        'default'     => 20,
                    ),
                    'page' => array(
                        'type'        => 'integer',
                        'description' => 'Page number',
                        'default'     => 1,
                    ),
                    'search' => array(
                        'type'        => 'string',
                        'description' => 'Search keyword',
                    ),
                ),
            ),
            'output_schema' => array(
                'type' => 'object',
            ),
            'permission_callback' => '__return_true',
            'execute_callback'    => 'mcp_reader_get_posts',
            'meta' => array(
                'mcp' => array( 'public' => true ),
                'annotations' => array( 'readonly' => true ),
            ),
        )
    );

    // Get single post by ID
    wp_register_ability(
        'reader/get-post',
        array(
            'category'    => 'reader',
            'label'       => __( 'Get Single Post', 'mcp-adapter-readonly-abilities' ),
            'description' => __( 'Returns a single post with full content by ID.', 'mcp-adapter-readonly-abilities' ),
            'input_schema' => array(
                'type'       => 'object',
                'required'   => array( 'id' ),
                'properties' => array(
                    'id' => array(
                        'type'        => 'integer',
                        'description' => 'Post ID',
                    ),
                ),
            ),
            'output_schema' => array(
                'type' => 'object',
            ),
            'permission_callback' => '__return_true',
            'execute_callback'    => 'mcp_reader_get_post',
            'meta' => array(
                'mcp' => array( 'public' => true ),
                'annotations' => array( 'readonly' => true ),
            ),
        )
    );

    // Get all pages
    wp_register_ability(
        'reader/get-pages',
        array(
            'category'    => 'reader',
            'label'       => __( 'Get All Pages', 'mcp-adapter-readonly-abilities' ),
            'description' => __( 'Returns all published pages with full content.', 'mcp-adapter-readonly-abilities' ),
            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'per_page' => array(
                        'type'        => 'integer',
                        'description' => 'Number of pages (default 20)',
                        'default'     => 20,
                    ),
                ),
            ),
            'output_schema' => array(
                'type' => 'object',
            ),
            'permission_callback' => '__return_true',
            'execute_callback'    => 'mcp_reader_get_pages',
            'meta' => array(
                'mcp' => array( 'public' => true ),
                'annotations' => array( 'readonly' => true ),
            ),
        )
    );

    // Get categories
    wp_register_ability(
        'reader/get-categories',
        array(
            'category'    => 'reader',
            'label'       => __( 'Get Categories', 'mcp-adapter-readonly-abilities' ),
            'description' => __( 'Returns all post categories.', 'mcp-adapter-readonly-abilities' ),
            'output_schema' => array(
                'type' => 'array',
            ),
            'permission_callback' => '__return_true',
            'execute_callback'    => 'mcp_reader_get_categories',
            'meta' => array(
                'mcp' => array( 'public' => true ),
                'annotations' => array( 'readonly' => true ),
            ),
        )
    );

    // Get tags
    wp_register_ability(
        'reader/get-tags',
        array(
            'category'    => 'reader',
            'label'       => __( 'Get Tags', 'mcp-adapter-readonly-abilities' ),
            'description' => __( 'Returns all post tags.', 'mcp-adapter-readonly-abilities' ),
            'output_schema' => array(
                'type' => 'array',
            ),
            'permission_callback' => '__return_true',
            'execute_callback'    => 'mcp_reader_get_tags',
            'meta' => array(
                'mcp' => array( 'public' => true ),
                'annotations' => array( 'readonly' => true ),
            ),
        )
    );
}

/**
 * Get posts with full content.
 *
 * @param array $input Input parameters.
 * @return array Posts data.
 */
function mcp_reader_get_posts( $input ) {
    $per_page = min( intval( $input['per_page'] ?? 20 ), 100 );
    $page = max( intval( $input['page'] ?? 1 ), 1 );

    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if ( ! empty( $input['search'] ) ) {
        $args['s'] = sanitize_text_field( $input['search'] );
    }

    $query = new WP_Query( $args );
    $posts = array();

    foreach ( $query->posts as $post ) {
        $posts[] = array(
            'id'         => $post->ID,
            'title'      => $post->post_title,
            'content'    => $post->post_content,
            'excerpt'    => get_the_excerpt( $post ),
            'url'        => get_permalink( $post->ID ),
            'date'       => $post->post_date,
            'modified'   => $post->post_modified,
            'author'     => get_the_author_meta( 'display_name', $post->post_author ),
            'categories' => wp_list_pluck( get_the_category( $post->ID ), 'name' ),
            'tags'       => wp_list_pluck( get_the_tags( $post->ID ) ?: array(), 'name' ),
        );
    }

    return array(
        'posts'       => $posts,
        'total'       => $query->found_posts,
        'total_pages' => $query->max_num_pages,
        'page'        => $page,
    );
}

/**
 * Get single post by ID.
 *
 * @param array $input Input parameters.
 * @return array Post data or error.
 */
function mcp_reader_get_post( $input ) {
    $post = get_post( intval( $input['id'] ) );

    if ( ! $post || $post->post_status !== 'publish' ) {
        return array( 'error' => 'Post not found' );
    }

    return array(
        'id'         => $post->ID,
        'title'      => $post->post_title,
        'content'    => $post->post_content,
        'excerpt'    => get_the_excerpt( $post ),
        'url'        => get_permalink( $post->ID ),
        'date'       => $post->post_date,
        'modified'   => $post->post_modified,
        'author'     => get_the_author_meta( 'display_name', $post->post_author ),
        'categories' => wp_list_pluck( get_the_category( $post->ID ), 'name' ),
        'tags'       => wp_list_pluck( get_the_tags( $post->ID ) ?: array(), 'name' ),
    );
}

/**
 * Get all published pages.
 *
 * @param array $input Input parameters.
 * @return array Pages data.
 */
function mcp_reader_get_pages( $input ) {
    $per_page = min( intval( $input['per_page'] ?? 20 ), 100 );

    $pages = get_pages( array(
        'post_status' => 'publish',
        'number'      => $per_page,
        'sort_column' => 'post_date',
        'sort_order'  => 'DESC',
    ) );

    $result = array();
    foreach ( $pages as $page ) {
        $result[] = array(
            'id'       => $page->ID,
            'title'    => $page->post_title,
            'content'  => $page->post_content,
            'url'      => get_permalink( $page->ID ),
            'date'     => $page->post_date,
            'modified' => $page->post_modified,
        );
    }

    return $result;
}

/**
 * Get all categories.
 *
 * @return array Categories data.
 */
function mcp_reader_get_categories() {
    $categories = get_categories( array( 'hide_empty' => false ) );
    $result = array();

    foreach ( $categories as $cat ) {
        $result[] = array(
            'id'    => $cat->term_id,
            'name'  => $cat->name,
            'slug'  => $cat->slug,
            'count' => $cat->count,
        );
    }

    return $result;
}

/**
 * Get all tags.
 *
 * @return array Tags data.
 */
function mcp_reader_get_tags() {
    $tags = get_tags( array( 'hide_empty' => false ) );
    $result = array();

    foreach ( $tags as $tag ) {
        $result[] = array(
            'id'    => $tag->term_id,
            'name'  => $tag->name,
            'slug'  => $tag->slug,
            'count' => $tag->count,
        );
    }

    return $result;
}
