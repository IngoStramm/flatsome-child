<?php 
add_action( 'cmb2_admin_init', 'page_register_metabox' );

function page_register_metabox() {

	$prefix = 'parcelamento_group_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb_group = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'Configuração de Parcelamento', 'cf' ),
		'object_types' => array( 'product', ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb_group->add_field( array(
		'id'          => $prefix . 'payments',
		'type'        => 'group',
		// 'description' => esc_html__( 'Parcelas', 'cf' ),
		'options'     => array(
			'group_title'   => esc_html__( 'Parcela {#}', 'cf' ), // {#} gets replaced by row number
			'add_button'    => esc_html__( 'Adicionar Nova Parcela', 'cf' ),
			'remove_button' => esc_html__( 'Remover Parcela', 'cf' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb_group->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Métodos de Pagamento', 'cmb2' ),
		'desc'    => esc_html__( 'Selecione o método de pagamento que será parcelado.', 'cmb2' ),
		'id'      => $prefix . 'gateway',
		'type'    => 'radio',
		'options' => get_all_payments()
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Número de parcelas sem juros', 'cmb2' ),
		'id'         => 'numero_parc_sem_juros',
		'type'       => 'select',
		'options'	 => get_total_parc()
	) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name'       => esc_html__( 'Número de parcelas com juros', 'cmb2' ),
	// 	'id'         => 'numero_parc_com_juros',
	// 	'type'       => 'select',
	// 	'options'	 => get_total_parc()
	// ) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name'       => esc_html__( 'Adicionar porcentagem da parcela com juros. O número da parcela é progressivo, continuando de onde o número de parcelas sem jurou parou. Porex: se o parcelamento for configurado 3 vezes sem juros, a o primeiro número de parcelas com juros  será em 4 vezes com juros. O seguinte, será em 5 vezes sem juros e assim por diante.', 'cmb2' ),
	// 	'id'         => 'title_parc_com_juros',
	// 	'type'       => 'title',
	// ) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Número de parcelas com juros', 'cmb2' ),
		'desc'    => esc_html__( 'Adicionar porcentagem da parcela com juros. O número da parcela é progressivo, continuando de onde o número de parcelas sem juro parou. Por ex: se o parcelamento for configurado 3 vezes sem juros, o primeiro número de parcelas com juros  será em 4 vezes com juros. O seguinte, será em 5 vezes sem juros e assim por diante.', 'cmb2' ),
		'id'   => 'numero_parc_com_juros',
		'type' => 'text',
		'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
		'repeatable' => true
	) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name'        => esc_html__( 'Description', 'cmb2' ),
	// 	'description' => esc_html__( 'Write a short description for this entry', 'cmb2' ),
	// 	'id'          => 'description',
	// 	'type'        => 'textarea_small',
	// ) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name' => esc_html__( 'Entry Image', 'cmb2' ),
	// 	'id'   => 'image',
	// 	'type' => 'file',
	// ) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name' => esc_html__( 'Image Caption', 'cmb2' ),
	// 	'id'   => 'image_caption',
	// 	'type' => 'text',
	// ) );

}
