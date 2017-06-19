<?php
$parcela = new Odin_Post_Type(
    __('Parcela', 'laf'), // Nome (Singular) do Post Type.
    'parcela' // Slug do Post Type.
);

$parcela->set_labels(
	array(
		'name'               => _x( 'Parcelas', 'post type general name', 'laf' ),
		'singular_name'      => _x( 'Parcela', 'post type singular name', 'laf' ),
		'menu_name'          => _x( 'Parcelas', 'admin menu', 'laf' ),
		'name_admin_bar'     => _x( 'Parcela', 'Adicionar Nova on admin bar', 'laf' ),
		'add_new'            => _x( 'Adicionar Nova', 'Parcela', 'laf' ),
		'add_new_item'       => __( 'Adicionar Nova Parcela', 'laf' ),
		'new_item'           => __( 'Nova Parcela', 'laf' ),
		'edit_item'          => __( 'Editar Parcela', 'laf' ),
		'view_item'          => __( 'Visualizar Parcela', 'laf' ),
		'all_items'          => __( 'Todas Parcelas', 'laf' ),
		'search_items'       => __( 'Pesquisar Parcelas', 'laf' ),
		'parent_item_colon'  => __( 'Parcelas Pai:', 'laf' ),
		'not_found'          => __( 'Nenhum Parcela encontrada.', 'laf' ),
		'not_found_in_trash' => __( 'Nenhum Parcela encontrada na lixeira.', 'laf' )
	)
);

$parcela->set_arguments(
	array(
		'supports' => array( 'title', 'revisions', 'page-attributes')
	)
);

add_action( 'admin_head', 'hide_yoast_parcelas' );

function hide_yoast_parcelas() {
	$utils = new Utils;
	$is_parcela = isset( $_GET['post_type'] ) ? $_GET['post_type'] == 'parcela' : false;
	if( $is_parcela ) : ?>
		<style>
			#wpseo_meta {
				display: none;
			}
		</style>
	<?php endif;
}