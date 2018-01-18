<?php 

// * Register custom post type Voice Speedy Project

function register_cpt_voice_project() {

	$labels = array(
		'name' => __( 'Voice Speedy Project', 'voicebunny-speedy' ),
		'singular_name' => __( 'Voice Speedy Projects', 'voicebunny-speedy' ),
		'add_new' => __( 'Add new', 'voicebunny-speedy' ),
		'add_new_item' => __( 'Add Voice Speedy Project', 'voicebunny-speedy' ),
		'edit_item' => __( 'Edit Voice Speedy Project', 'voicebunny-speedy' ),
		'new_item' => __( 'New Voice Speedy Project', 'voicebunny-speedy' ),
		'view_item' => __( 'View Voice Speedy Project', 'voicebunny-speedy' ),
		'search_items' => __( 'Search Voice Speedy Project', 'voicebunny-speedy' ),
		'not_found' => __( 'Results not found', 'voicebunny-speedy' ),
		'not_found_in_trash' => __( 'There are not items in the trash', 'voicebunny-speedy' ),
		'parent_item_colon' => __( 'Parent Voice Speedy Project:', 'voicebunny-speedy' ),
		'menu_name' => __( 'Voice Speedy Projects', 'voicebunny-speedy' ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'description' => __('Create a Voice Speedy Project in VoiceBunny', 'voicebunny-speedy'),
		'supports' => array( 'custom-fields', 'title' ),
		'taxonomies' => array( 'category' ),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'voiceproject', $args );

	flush_rewrite_rules();
}

add_action( 'init', 'register_cpt_voice_project' );