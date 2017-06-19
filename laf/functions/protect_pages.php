<?php
add_action( 'pre_get_posts', 'my_hide_system_pages' );

function my_hide_system_pages( $query ) {
	$utils = new Utils;
	$administrador = current_user_can('manage_options');
	if( is_admin() && !empty( $_GET['post_type'] ) && $_GET['post_type'] == 'page' && $query->query['post_type'] == 'page' && !$administrador ) {
		$query->set( 
			'meta_query', array(
				'relation' => 'OR',
				array(
					'key' => 'protect_page_active',
					'value' => 'on',
					'compare' => '!='
				),
				array(
					'key' => 'protect_page_active',
					'compare' => 'NOT EXISTS'
				),
			)
		);
	}
}

add_action( 'admin_bar_menu', 'remove_bar_menu_page_edit', 999 );

function remove_bar_menu_page_edit( $wp_admin_bar ) {
	$utils = new Utils;
	global $post;
	$administrador = current_user_can('manage_options');
	$post_id = $post->ID;
	$protege_page = get_post_meta( $post_id, 'protect_page_active', true );
	if( !$administrador && $protege_page == 'on' ) :
		$wp_admin_bar->remove_node( 'edit' );
	endif;
}