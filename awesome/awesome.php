<?php
/**
 * Plugin Name:       Awesome
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       awesome
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_awesome_block_init() {
	register_block_type( __DIR__ . '/build/awesome' );
}
add_action( 'init', 'create_block_awesome_block_init' );

function registreer_boek_cpt()
{
    $labels = array(
        'name'               => 'Boeken',
        'singular_name'      => 'Boek',
        'menu_name'          => 'Boeken',
        'name_admin_bar'     => 'Boek Toevoegen',
        'add_new'            => 'Nieuw Boek',
        'add_new_item'       => 'Nieuw Boek Toevoegen',
        'edit_item'          => 'Boek Bewerken',
        'new_item'           => 'Nieuw Boek',
        'view_item'          => 'Bekijk Boek',
        'search_items'       => 'Zoek Boeken',
        'not_found'          => 'Geen boeken gevonden',
        'not_found_in_trash' => 'Geen boeken in de prullenbak'
    );

    $args = array(
        'label'               => 'boeken',
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'boeken'),
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-book', // WordPress dashicon voor een boek
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'        => true, // Nodig voor Gutenberg en de REST API
    );

    register_post_type('boek', $args);
}
add_action('init', 'registreer_boek_cpt');

