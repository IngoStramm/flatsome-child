<?php 
// Adiciona o botão para comprar o produto nas páginas de listagem de produtos da Loja

// add_action( 'woocommerce_after_shop_loop_item_title', 'cf_template_single_add_to_cart', 10 );

function cf_template_single_add_to_cart() {
	?>
	<div class="add-to-cart-small">
		<?php woocommerce_template_single_add_to_cart(); ?>
	</div>
	<!-- /.add-to-cart-small -->
	<?php
}

add_filter( 'woocommerce_after_edit_address_form_billing', 'fix_clearfix_profile_address' );

function fix_clearfix_profile_address() {
	?>
	<div class="clearfix"></div>
	<?php
}

// Woocommerce Customiza campos do checkout
// Referência @link: https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
add_filter( 'woocommerce_billing_fields' , 'custom_override_checkout_fields', 10 );
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields', 10 );
  
function custom_override_checkout_fields( $get_fields ) {

	$utils = new Utils;
	unset($get_fields['order']['order_comments']);
	$fields = is_checkout() ? $get_fields['billing'] : $get_fields;
	$tipo_pessoa_habilitado = isset( $fields['billing_persontype'] );
	$ie = isset( $fields['billing_ie'] );
	$pf = isset( $fields['billing_cpf'] );
	$pj = isset( $fields['billing_cnpj'] );


	if( !isset( $fields['billing_full_name'] ) ) :
		$fields['billing_full_name'] = array(
			'type' 					=> 'text',
			'label' 				=> __( 'Nome Completo', 'laf' ),
			'placeholder' 			=> __( 'Nome Completo', 'laf' ),
			'class' 				=> ( $tipo_pessoa_habilitado ) ? array( 'form-row', 'form-row-first', 'nome-completo' ) : array( 'form-row', 'form-row-wide', 'nome-completo' ),
			'required' 				=> true,
			'clear' 				=> false,
			'autocomplete' 			=> 'full-name',
			'priority'				=> 1
		);
	endif;

	if( $tipo_pessoa_habilitado ) :

		$fields['billing_persontype']['class'] = array( 'form-row', 'form-row-last');
		// $fields['billing_persontype']['options'][0] = $fields['billing_persontype']['label'];
		$fields['billing_persontype']['clear '] = true;
		$fields['billing_persontype']['priority'] = 2;

	endif;

	if( $pf ) :

		$fields['billing_cpf']['class'] = array( 'form-row', 'form-row-first');
		$fields['billing_cpf']['placeholder'] = $fields['billing_cpf']['label'];
		$fields['billing_cpf']['priority'] = 3;

		$fields['billing_rg']['class'] = array( 'form-row', 'form-row-last', 'rg-mask');
		$fields['billing_rg']['placeholder'] = $fields['billing_rg']['label'];
		$fields['billing_rg']['priority'] = 4;

	endif;

	if( $pj ) :

		$fields['billing_cnpj']['class'] = array( 'form-row', 'form-row-first');
		$fields['billing_cnpj']['placeholder'] = $fields['billing_cnpj']['label'];
		$fields['billing_cnpj']['priority'] = 5;

		$fields['billing_company']['class'] = array( 'form-row', 'form-row-last');
		$fields['billing_company']['placeholder'] = $fields['billing_company']['label'];
		$fields['billing_company']['priority'] = 7;

	else :

		unset( $fields['billing_company'] );

	endif;

	if( $ie ) :

		$fields['billing_ie']['class'] = array( 'form-row', 'form-row-last');
		$fields['billing_ie']['placeholder'] = $fields['billing_ie']['label'];
		$fields['billing_ie']['priority'] = 6;
	
	endif;

	$fields['billing_email']['class'] = array( 'form-row', 'form-row-first');
	$fields['billing_email']['label'] = __( 'Endereço de e-mail (Será seu login)', 'cf' );
	$fields['billing_email']['placeholder'] = $fields['billing_email']['label'];
	$fields['billing_email']['priority'] = 8;

	$fields['billing_phone']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-first') : array( 'form-row', 'form-row-last');
	$fields['billing_phone']['placeholder'] = __( 'Telefone para Contato', 'cf' );
	$fields['billing_phone']['priority'] = 9;

	$fields['billing_postcode']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-last') : array( 'form-row', 'form-row-first');
	$fields['billing_postcode']['placeholder'] = $fields['billing_postcode']['label'];
	$fields['billing_postcode']['priority'] = 10;

	$fields['billing_address_1']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-first', 'disabled-input') : array( 'form-row', 'form-row-last', 'disabled-input');
	$fields['billing_address_1']['placeholder'] = $fields['billing_address_1']['label'];
	$fields['billing_address_1']['priority'] = 50;

	$fields['billing_neighborhood']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-last', 'disabled-input') : array( 'form-row', 'form-row-first', 'disabled-input');
	$fields['billing_neighborhood']['placeholder'] = $fields['billing_neighborhood']['label'];
	$fields['billing_neighborhood']['priority'] = 52;

	$fields['billing_number']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-first') : array( 'form-row', 'form-row-last');
	$fields['billing_number']['placeholder'] = $fields['billing_number']['label'];
	$fields['billing_number']['priority'] = 53;

	$fields['billing_address_2']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-last') : array( 'form-row', 'form-row-first');
	$fields['billing_address_2']['placeholder'] = $fields['billing_address_2']['label'];
	$fields['billing_address_2']['priority'] = 51;

	$fields['billing_city']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-first') : array( 'form-row', 'form-row-last');
	$fields['billing_city']['placeholder'] = $fields['billing_city']['label'];
	$fields['billing_city']['priority'] = 54;

	$fields['billing_state']['class'] = ( !is_user_logged_in() ) ? array( 'form-row', 'form-row-last') : array( 'form-row', 'form-row-first');
	$fields['billing_state']['placeholder'] = $fields['billing_state']['label'];
	$fields['billing_state']['priority'] = 55;

	$fields['billing_first_name']['class'] = array( 'form-row', 'form-row-first', 'primeiro-nome', 'hidden');
	$fields['billing_first_name']['placeholder'] = $fields['billing_first_name']['label'];
	$fields['billing_first_name']['priority'] = 90;
	$fields['billing_first_name']['required'] = false;

	$fields['billing_last_name']['class'] = array( 'form-row', 'form-row-last', 'sobrenome', 'hidden');
	$fields['billing_last_name']['placeholder'] = $fields['billing_last_name']['label'];
	$fields['billing_last_name']['priority'] = 91;
	$fields['billing_last_name']['required'] = false;

	if( !is_user_logged_in() && isset( $get_fields['account'] ) ) :

		$account_fields = $get_fields['account'];

		unset( $get_fields['account'] );

		foreach ($account_fields as $k => $account_field) :
			$fields[$k] = $account_field;
			$fields[$k]['placeholder'] = __( 'Senha de Cadastro', 'cf' );
			$fields[$k]['class'] = array( 'form-row-last' );
			$fields[$k]['priority'] = 8;
		endforeach;

	endif;

	if( isset( $fields['billing'] ) )
		unset( $fields['billing'] );


	if( is_checkout() ) :
		$get_fields['billing'] = $fields;
		foreach ($fields as $k => $field) :
		endforeach;
		return $get_fields;
	else :
		return $fields;
	endif;
}

// Valida o novo campo customizado

add_action('woocommerce_checkout_process', 'nome_completo_checkout_field_process');

function nome_completo_checkout_field_process() {
    // Check if set, if its not set add an error.
    if( !strpos( trim( $_POST['billing_full_name'] ), ' ' ) )
        wc_add_notice( __( '"<strong>Nome Completo"</strong> precisa de um Nome e um Sobrenome.', 'cf' ), 'error' );
}

// Remove verificação de segurança da senha

add_action( 'wp_print_scripts', 'cf_remove_password_strength', 100 );

function cf_remove_password_strength() {
	if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
		wp_dequeue_script( 'wc-password-strength-meter' );
	}
}

// Posiciona o Cupom e Formulário de login abaixo dos campos de checkout

// remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
// add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_login_form', 10 );
add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

function cf_product_upsell_sidebar(){
  // Product Upsell
    if(get_theme_mod('product_upsell','sidebar') == 'sidebar') {
        remove_action( 'woocommerce_after_single_product_summary' , 'woocommerce_upsell_display', 15);
        add_action('cf_before_product_sidebar','woocommerce_upsell_display', 2);
    }
    else if(get_theme_mod('product_upsell', 'sidebar') == 'disabled') {
        remove_action( 'woocommerce_after_single_product_summary' , 'woocommerce_upsell_display', 15);
    }
}

// Move os Produtos Relacionados e Mais Vendidos para antes das abas do produto

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 8 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 9 );

//Used for mobile application login
add_action('wo_before_api', 'wo_cors_check_and_response');

function wo_cors_check_and_response(){
	header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
}

add_action( 'woocommerce_after_shop_loop_item_title', 'get_best_parcela_sem_juros', 10 );
add_action( 'woocommerce_single_product_summary', 'get_best_parcela_sem_juros', 10 );
add_action( 'woocommerce_single_product_summary', 'add_parcelamento', 10 );
add_action( 'woocommerce_single_product_lightbox_summary', 'get_best_parcela_sem_juros', 100 );
// add_action( 'woocommerce_single_product_lightbox_summary', 'exibe_parcelamentos_tabs', 100 );

function add_parcelamento() {
	$utils = new Utils;
	$post_id = get_the_id();
	$prod = wc_get_product( $post_id );
	$price_html = $prod->get_price_html();
	$gateways_ativos = get_parcelas_arr();
	// $utils->debug( $gateways_ativos );
	if( $gateways_ativos && $price_html ) :
		$lightbox_content .= parcelamentos_tabs();
		echo do_shortcode( '[button class="m-t-0-i m-b-0-i" style="link" size="xsmall" color="success" text="' . __( 'ver parcelas', 'cf' ) . '" link="#parcelas-' . $post_id . '"]' );
		echo do_shortcode( '[lightbox id="parcelas-' . $post_id . '" width="600px" padding="40px"]' . $lightbox_content . '[/lightbox]', false );
	endif;
}

function parcelamentos_tabs() {
	$utils = new Utils;
	$content = '[tabgroup title="' . __( 'Parcelas', 'laf' ) . '" style="tabs" nav_size="small" align="center"]';
	$post_id = get_the_id();
	$prod = wc_get_product( $post_id );
	$price = $prod->get_price();
	$parcelas = get_parcelas_arr();
	foreach ( $parcelas as $parc ) :
		$nome = $parc['title'];
		$vezes_sem_juros = $parc['vezes_sem_juros'];
		$vezes_com_juros = $parc['vezes_com_juros'];
		$juros = $parc['juros'];
		// $utils->debug( $ativo );
		// $utils->debug( $nome );
		// $utils->debug( $parc_com_juros );
		// $utils->debug( $vezes_sem_juros );
		$content .= '[tab title="' . $nome . '"]';
		$content .= '<ul>';
		$numero_vezes = 1;
		for ( $i  = 0; $i < $vezes_sem_juros; $i++ ) :
			$parcela = new Parcela( $numero_vezes, $price );
			$content .= '<li class="bullet-checkmark">' . $parcela->getNumeroVezes() . 'x (' . wc_price( $parcela->getValorParcela() ) . ') sem juros. Total: ' . wc_price( $parcela->getValorTotalParcelas() ) . '</li>';
			$numero_vezes++;
		endfor;
		if( $vezes_com_juros > 0 ) :
			foreach ( $juros as $juro ) :
				$parcela = new Parcela( $numero_vezes, $price, $juro );
				$content .= '<li class="bullet-checkmark">' . $parcela->getNumeroVezes() . 'x (' . wc_price( $parcela->getValorParcela() ) . ') com juros de ' . $parcela->getDisplayJuros() . '% ao mês. Total: ' . wc_price( $parcela->getValorTotalParcelas() ) . '</li>';
				$numero_vezes++;
			endforeach;
		endif;
		$content .= '</ul>';
		$content .= '[/tab]';
	endforeach;
	$content .= '[/tabgroup]';
	return $content;
}

function exibe_parcelamentos_tabs() {
	echo do_shortcode( parcelamentos_tabs(), false );
}

function get_best_parcela_sem_juros() {
	$utils = new Utils;
	$count = 0;
	$post_id = get_the_id();
	$prod = wc_get_product( $post_id );
	$price = $prod->get_price();
	$parcelas = get_parcelas_arr();
	$best_parcela = new Parcela( 1, $price );
	$best_parcela_nome = null;
	foreach ( $parcelas as $parc ) :

				$nome = $parc['title'];
				$vezes_sem_juros = $parc['vezes_sem_juros'];
				$numero_vezes = 1;
				for ( $i  = 0; $i < $vezes_sem_juros; $i++ ) :
					$parcela = new Parcela( $numero_vezes, $price );
					if( $parcela->getValorParcela() < $best_parcela->getValorParcela() ) :
						$best_parcela_nome = $nome;
						$best_parcela = $parcela;
					endif;
					$numero_vezes++;
				endfor;
	endforeach;
	echo $best_parcela_nome ? '<p class="m-b-0">' . $best_parcela->getNumeroVezes() . 'x de ' . wc_price( $best_parcela->getValorParcela() ) . ' sem juros</p>' : '';
}

function get_parcelas_arr() {
	$utils = new Utils;
	$parcelas = [];
	$args = array(
		'post_type' => 'parcela',
		'order'		=> 'ASC',
		'orderby'	=> 'menu_order'
	);
	$the_query = new WP_Query( $args ); 
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$post_id = get_the_ID();
			$vezes_sem_juros = get_post_meta( $post_id, 'numero_parc_sem_juros', true );
			$vezes_com_juros = get_post_meta( $post_id, 'numero_parc_com_juros', true );
			$parc = array(
				'id'				=> $post_id,
				'title'				=> get_the_title( $post_id ),
				'vezes_sem_juros'	=> $vezes_sem_juros,
				'vezes_com_juros'	=> count( $vezes_com_juros ),
				'juros'	=> $vezes_com_juros,
			);
			$parcelas[] = $parc;
		endwhile;
		wp_reset_postdata();
	endif;
	return $parcelas;
}

add_filter( 'woocommerce_product_review_comment_form_args', 'cf_edit_comment_form' );

function cf_edit_comment_form( $comment_form ) {
	$utils = new Utils;
	$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . __( 'Sua nota', 'cf' ) .'</label><select name="rating" id="rating">
							<option value="">' . __( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . __( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . __( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . __( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . __( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . __( 'Very Poor', 'woocommerce' ) . '</option>
						</select></p>';
	$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Sua Avaliação', 'cf' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
	// $utils->debug( $comment_form );
	return $comment_form;
}