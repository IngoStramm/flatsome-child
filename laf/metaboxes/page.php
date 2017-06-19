<?php 
add_action( 'cmb2_admin_init', 'produto_register_metabox' );

function produto_register_metabox() {

	$administrador = current_user_can('manage_options');
	$prefix = 'protect_page_';

	if( $administrador ) :

		$cmb_demo = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => esc_html__( 'Página de Sistema', 'cf' ),
			'object_types'  => array( 'page', ), // Post type
			// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
			'context'    => 'side',
			// 'priority'   => 'high',
			// 'show_names' => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
			// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
		) );

		$cmb_demo->add_field( array(
			'name' => esc_html__( 'Proteger Página', 'cmb2' ),
			'desc' => esc_html__( 'Quando esta opção está ativada, a página fica invisível para os usuários que não forem administradores.', 'cmb2' ),
			'id'   => $prefix . 'active',
			'type' => 'checkbox',
		) );

	endif;

}