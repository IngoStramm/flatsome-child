<?php 
add_action( 'cmb2_admin_init', 'parcela_register_metabox' );

function parcela_register_metabox() {

	$prefix = 'parcela_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb_parcela = new_cmb2_box( array(
		'id'           => $prefix . 'options',
		'title'        => esc_html__( 'Opções de Parcelamento', 'cf' ),
		'object_types' => array( 'parcela', ),
	) );

/*	$cmb_parcela->add_field( array(
		'name' => esc_html__( 'Ativar', 'cf' ),
		'id'         => 'ativo',
		'type'       => 'checkbox',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

*/	$cmb_parcela->add_field( array(
		'name'       => esc_html__( 'Número de vezes sem juros', 'cf' ),
		'id'         => 'numero_parc_sem_juros',
		'type'       => 'select',
		'options'	 => get_total_parc()
	) );

	$cmb_parcela->add_field( array(
		'name' => esc_html__( 'Número de vezes com juros', 'cmb2' ),
		'desc'    => esc_html__( 'Adicionar porcentagem da parcela com juros. O número da parcela é progressivo, continuando de onde o número de parcelas sem juros parou. Por ex: se o parcelamento for configurado 3 vezes sem juros, o primeiro número de parcelas com juros  será em 4 vezes com juros. O seguinte, será em 5 vezes sem juros e assim por diante.', 'cf' ),
		'id'   => 'numero_parc_com_juros',
		'type' => 'text',
		'attributes' => array(
				'type' => 'number',
				'pattern' => '[0-9]+([\.,][0-9]+)?',
				'step' => '0.5'
			),
		'repeatable' => true
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	/*$group_field_id = $cmb_parcela->add_field( array(
		'id'          => $prefix . 'vez',
		'type'        => 'group',
		'description' => esc_html__( 'Configuração de cada parcela', 'cmb2' ),
		'options'     => array(
			'group_title'   => esc_html__( 'Parcela {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => esc_html__( 'Adicionar nova parcela', 'cmb2' ),
			'remove_button' => esc_html__( 'Remover parcela', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );*/

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	/*$cmb_parcela->add_group_field( $group_field_id, array(
		'name'        => esc_html__( 'Description', 'cmb2' ),
		'description' => esc_html__( 'Write a short description for this entry', 'cmb2' ),
		'id'          => 'description',
		'type'        => 'textarea_small',
	) );

	$cmb_parcela->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Entry Image', 'cmb2' ),
		'id'   => 'image',
		'type' => 'file',
	) );

	$cmb_parcela->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Image Caption', 'cmb2' ),
		'id'   => 'image_caption',
		'type' => 'text',
	) );*/

}

function get_total_parc() {
	$parc = [];
	$total_parc = 32;
	for ( $i = 1; $i <= $total_parc; $i++ ) :
		$parc[$i] = $i;
	endfor;
	return $parc;
}

