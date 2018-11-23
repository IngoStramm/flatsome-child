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
add_filter( 'woocommerce_shipping_fields' , 'custom_override_checkout_fields', 10 );
  
function custom_override_checkout_fields( $get_fields ) {

	$utils = new Utils;
	$key = isset( $get_fields[ 'shipping_first_name' ] ) ? 'shipping' : 'billing';
	// unset($get_fields['order']['order_comments']);
	$fields = is_checkout() && $key == 'billing' ? $get_fields[ $key ] : $get_fields;
	$tipo_pessoa_habilitado = isset( $fields[ $key . '_persontype'] );
	$wcbcf_settings = get_option( 'wcbcf_settings', true );
	$show_rg = array_key_exists( 'rg', $wcbcf_settings );
	$ie = isset( $fields[ $key . '_ie'] );
	$pf = isset( $fields[ $key . '_cpf'] );
	$pj = isset( $fields[ $key . '_cnpj'] );

	if( !isset( $fields[ $key . '_full_name'] ) ) :
		$fields[ $key . '_full_name'] = array(
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

	if( $key == 'billing' && $tipo_pessoa_habilitado ) :

		$fields[ $key . '_persontype']['class'] = array( 'form-row', 'form-row-last');
		// $fields[ $key . '_persontype']['options'][0] = $fields[ $key . '_persontype']['label'];
		$fields[ $key . '_persontype']['clear '] = true;
		$fields[ $key . '_persontype']['priority'] = 2;

	endif;

	if( $key == 'billing' && $pf ) :

		$fields[ $key . '_cpf']['class'] = array( 'form-row', 'form-row-first');
		$fields[ $key . '_cpf']['placeholder'] = $fields[ $key . '_cpf']['label'];
		$fields[ $key . '_cpf']['priority'] = 3;

		if( $show_rg ) :

			$fields[ $key . '_rg']['class'] = array( 'form-row', 'form-row-last', 'rg-mask');
			$fields[ $key . '_rg']['placeholder'] = $fields[ $key . '_rg']['label'];
			$fields[ $key . '_rg']['priority'] = 4;

		else :

			// $fields[ $key . '_full_name'][ 'class' ] = array( 'form-row', 'form-row-wide', 'nome-completo' );
			// $fields[ $key . '_persontype']['class'] =  array( 'form-row', 'form-row-first' );
			$fields[ $key . '_cpf']['class'] = array( 'form-row', 'form-row-wide');

		endif;

	endif;

	if( $key == 'billing' && $pj ) :

		$fields[ $key . '_cnpj']['class'] = array( 'form-row', 'form-row-first');
		$fields[ $key . '_cnpj']['placeholder'] = $fields[ $key . '_cnpj']['label'];
		$fields[ $key . '_cnpj']['priority'] = 5;

		$fields[ $key . '_company']['class'] = array( 'form-row', 'form-row-last');
		$fields[ $key . '_company']['placeholder'] = $fields[ $key . '_company']['label'];
		$fields[ $key . '_company']['priority'] = 7;

	else :

		unset( $fields[ $key . '_company'] );

	endif;

	if( $key == 'billing' && $ie ) :

		$fields[ $key . '_ie']['class'] = array( 'form-row', 'form-row-last');
		$fields[ $key . '_ie']['placeholder'] = $fields[ $key . '_ie']['label'];
		$fields[ $key . '_ie']['priority'] = 6;
	
	endif;

	if( $key == 'billing' ) :

		$fields[ $key . '_email']['class'] = array( 'form-row', 'form-row-first');
		$fields[ $key . '_email']['label'] = __( 'Endereço de e-mail (Será seu login)', 'cf' );
		$fields[ $key . '_email']['placeholder'] = $fields[ $key . '_email']['label'];
		$fields[ $key . '_email']['priority'] = 8;	

	endif;

	if( $key == 'billing' ) :

		$fields[ $key . '_phone']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-first') : array( 'form-row', 'form-row-last');
		$fields[ $key . '_phone']['placeholder'] = __( 'Telefone para Contato', 'cf' );
		$fields[ $key . '_phone']['priority'] = 9;

	endif;

	$fields[ $key . '_postcode']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-last') : array( 'form-row', 'form-row-first');
	$fields[ $key . '_postcode']['placeholder'] = $fields[ $key . '_postcode']['label'];
	$fields[ $key . '_postcode']['priority'] = 10;

	$fields[ $key . '_address_1']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-first', 'disabled-input') : array( 'form-row', 'form-row-last', 'disabled-input');
	$fields[ $key . '_address_1']['placeholder'] = $fields[ $key . '_address_1']['label'];
	$fields[ $key . '_address_1']['priority'] = 50;

	$fields[ $key . '_number']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-last') : array( 'form-row', 'form-row-first');
	$fields[ $key . '_number']['placeholder'] = $fields[ $key . '_number']['label'];
	$fields[ $key . '_number']['priority'] = 51;

	$fields[ $key . '_address_2']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-first') : array( 'form-row', 'form-row-last');
	$fields[ $key . '_address_2']['placeholder'] = $fields[ $key . '_address_2']['label'];
	$fields[ $key . '_address_2']['priority'] = 52;

	$fields[ $key . '_neighborhood']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-last', 'disabled-input') : array( 'form-row', 'form-row-first', 'disabled-input');
	$fields[ $key . '_neighborhood']['placeholder'] = $fields[ $key . '_neighborhood']['label'];
	$fields[ $key . '_neighborhood']['priority'] = 53;

	// $fields[ $key . '_city']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-first') : array( 'form-row', 'form-row-last');
	$fields[ $key . '_city']['class'] = array( 'form-row', 'form-row-last');
	$fields[ $key . '_city']['placeholder'] = $fields[ $key . '_city']['label'];
	$fields[ $key . '_city']['priority'] = 54;

	// $fields[ $key . '_state']['class'] = ( !$show_rg ) ? array( 'form-row', 'form-row-last') : array( 'form-row', 'form-row-first');
	$fields[ $key . '_state']['class'] = array( 'form-row', 'form-row-first');
	$fields[ $key . '_state']['placeholder'] = $fields[ $key . '_state']['label'];
	$fields[ $key . '_state']['priority'] = 55;

	$fields[ $key . '_first_name']['class'] = array( 'form-row', 'form-row-first', 'primeiro-nome', 'hidden' );
	$fields[ $key . '_first_name']['placeholder'] = $fields[ $key . '_first_name']['label'];
	$fields[ $key . '_first_name']['priority'] = 90;
	$fields[ $key . '_first_name']['required'] = false;

	$fields[ $key . '_last_name']['class'] = array( 'form-row', 'form-row-last', 'sobrenome', 'hidden' );
	$fields[ $key . '_last_name']['placeholder'] = $fields[ $key . '_last_name']['label'];
	$fields[ $key . '_last_name']['priority'] = 91;
	$fields[ $key . '_last_name']['required'] = false;

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

	if( $key == 'billing' && isset( $fields[ $key ] ) )
		unset( $fields[ $key ] );

	// unset( $fields[ 'shipping_company' ] );

	if( is_checkout() && $key == 'billing' ) :
		$get_fields['billing'] = $fields;
		foreach ($fields as $k => $field) :
		endforeach;
		return $get_fields;
	else :
		return $fields;
	endif;
}

// add_filter( 'woocommerce_shipping_fields' , 'custom_override_shipping_fields', 10 );

function custom_override_shipping_fields( $get_fields ) {
	$utils = new Utils;
	$fields = $get_fields;
	$utils->debug( $fields[ 'shipping_company' ] );
	unset( $fields[ 'shipping_company' ] );


	// $fields = is_checkout() ? $get_fields['billing'] : $get_fields;	
	return $fields;
}


// Valida o novo campo customizado

add_action('woocommerce_checkout_process', 'cf_validation_checkout_field_process');

function cf_validation_checkout_field_process() {

    if( !strpos( trim( $_POST['billing_full_name'] ), ' ' ) )
        wc_add_notice( __( '<strong>"Nome Completo"</strong> precisa de um Nome e um Sobrenome.', 'cf' ), 'error' );

    if( empty( $_POST['billing_first_name'] ) || empty( $_POST['billing_last_name'] ) )
        wc_add_notice( __( 'Por favor, preencha novamente o campo <strong>"Nome Completo"</strong>. Precisamos atualizar os dados dos nossos clientes, esse procedimento só será solicitado uma vez.', 'cf' ), 'error' );
}

// Remove verificação de segurança da senha

// add_action( 'wp_print_scripts', 'cf_remove_password_strength', 100 );

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
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 8 );
// add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 9 );

//Used for mobile application login
add_action('wo_before_api', 'wo_cors_check_and_response');

function wo_cors_check_and_response(){
	header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
}

// add_action( 'woocommerce_after_shop_loop_item_title', 'get_best_parcela_sem_juros', 10 );
add_filter( 'woocommerce_loop_add_to_cart_link', 'add_best_parcela_and_add_cto_cart_link', 10, 2 );
add_action( 'woocommerce_single_product_summary', 'get_best_parcela_sem_juros', 10 );
add_action( 'woocommerce_single_product_summary', 'add_parcelamento', 10 );
add_action( 'woocommerce_single_product_lightbox_summary', 'get_best_parcela_sem_juros', 100 );
// add_action( 'woocommerce_single_product_lightbox_summary', 'exibe_parcelamentos_tabs', 100 );

function add_best_parcela_and_add_cto_cart_link( $link, $product ) {
	return $link . get_best_parcela_sem_juros();
}

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
		// $utils->debug( $vezes_sem_juros );
		// $utils->debug( $vezes_com_juros );
		$content .= '[tab title="' . $nome . '"]';
		$content .= '<ul>';
		$numero_vezes = 1;
		for ( $i  = 0; $i < $vezes_sem_juros; $i++ ) :
			$parcela = new Parcela( $numero_vezes, $price );
			$content .= '<li class="bullet-checkmark">' . $parcela->getNumeroVezes() . 'x (' . wc_price( $parcela->getValorParcela() ) . ') sem juros. Total: ' . wc_price( $parcela->getValorTotalParcelas() ) . '</li>';
			$numero_vezes++;
		endfor;
		if( $vezes_com_juros > 0 && $vezes_com_juros != null ) :
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
			$num_vezes_com_juros = empty( $vezes_com_juros ) ? 0 : count( $vezes_com_juros );
			$parc = array(
				'id'				=> $post_id,
				'title'				=> get_the_title( $post_id ),
				'vezes_sem_juros'	=> $vezes_sem_juros,
				'vezes_com_juros'	=> $num_vezes_com_juros,
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

/*
 *
 * Limita o número de caracteres do título dos produtos
 *
 */

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'cf_template_loop_product_title', 10 );

function cf_template_loop_product_title() {
	$title = get_the_title( );
	// $title = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sed luctus arcu, non congue tortor.'; //debug
	$short_title = wp_trim_words( $title, 12, '' );
	echo '<h2 class="woocommerce-loop-product__title cf-wc-loop-product-title" title="' . $title . '">' . $short_title . '</h2>';
}

add_action( 'wp_footer', 'cart_update_qty_script' );

function cart_update_qty_script() {
	if (is_cart()) :
		?>
		<script>
			jQuery('div.woocommerce').on('change', '.qty', function(){
				jQuery("[name='update_cart']").removeAttr('disabled');
				jQuery("[name='update_cart']").trigger("click"); 
			});
		</script>
		<?php
	endif;
}