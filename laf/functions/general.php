<?php 
// Adiciona os scripts e estilos customizados

add_action( 'wp_enqueue_scripts', 'cf_enqueue_styles' );

function cf_enqueue_styles() {
	$min = ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) ? '' : '.min';
	wp_enqueue_style( 'cf-style-legacy', get_stylesheet_directory_uri() . '/assets/css/style-legacy.css', array(), '1.0.0' );
	wp_enqueue_style( 'cf-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array( 'cf-style-legacy' ), '0.0.1' );
	wp_enqueue_script( 'jquery-mask', get_stylesheet_directory_uri() . '/assets/js/jquery.mask' . $min . '.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'cf-main-script', get_stylesheet_directory_uri() . '/assets/js/main' . $min . '.js', array( 'jquery', 'jquery-mask' ), '1.0.1', true );
}

// Altera a versão do jQuery usada pelo Wordpress
// Fix para a Busca do CEP nos Correios retornar o erro

add_action('init', 'modify_jquery_version');

function modify_jquery_version() {
	if (!is_admin()) {
		$min = ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) ? '' : '.min';
		wp_deregister_script('jquery');
		wp_register_script('jquery',
			'https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery' . $min . '.js', false, '2.0.s');
		wp_enqueue_script('jquery');
	}
}

// Otimização de performance

add_action( 'wp_enqueue_scripts', 'cf_dequeue_scripts', 9999 );

function cf_dequeue_scripts() {

    $load_cf7_scripts = false;
    $load_fb_comments_scripts = false;

    if( is_singular() ) {
    	$post = get_post();

    	if( has_shortcode($post->post_content, 'contact-form-7') ) {
        	$load_cf7_scripts = true;
    	}

    	if( has_shortcode($post->post_content, 'wpdevart_facebook_comment') ) {
        	$load_fb_comments_scripts = true;
    	}

    }

    if( ! $load_cf7_scripts ) {
        wp_dequeue_script( 'contact-form-7' );
        wp_dequeue_style( 'contact-form-7' );
    }

    if( !$load_fb_comments_scripts ) {
		remove_action( 'wp_footer', array( 'wpdevart_comment_main', 'generete_facbook_js_sdk' ) );
    }

    // Desabilita o thickbox.js (obsoleto, apenas plugins antigos usam)
    wp_deregister_script('thickbox');

}

// Desabilita confirmação de mudança de e-mail administrativo

remove_action( 'add_option_new_admin_email', 'update_option_new_admin_email' );
remove_action( 'update_option_new_admin_email', 'update_option_new_admin_email' );

/**
 * Disable the confirmation notices when an administrator
 * changes their email address.
 *
 * @see http://codex.wordpress.com/Function_Reference/update_option_new_admin_email
 */
function wpdocs_update_option_new_admin_email( $old_value, $value ) {

    update_option( 'admin_email', $value );
}
add_action( 'add_option_new_admin_email', 'wpdocs_update_option_new_admin_email', 10, 2 );
add_action( 'update_option_new_admin_email', 'wpdocs_update_option_new_admin_email', 10, 2 );