<?php
add_action( 'flatsome_before_comments', 'add_fb_comments', 10, 1 );

function add_fb_comments() {
	if( class_exists( 'wpdevart_comment_main' ) ) :
		$link = get_permalink();
		echo do_shortcode('[wpdevart_facebook_comment curent_url="' . $link . '" order_type="social" title_text="' . __( 'Comentários', 'laf' ) . '" title_text_color="#555555" title_text_font_size="20" title_text_font_famely="lato" title_text_position="left" width="100%" bg_color="#d4d4d4" animation_effect="random" count_of_comments="3" ]');
	endif;
}

