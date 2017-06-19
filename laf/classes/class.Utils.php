<?php
class Utils {
	public function debug($a) {
		echo '<pre>';
		var_dump($a);
		echo '</pre>';
	}

	public function nl2p($string, $line_breaks = true) {
		$string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);

		// It is conceivable that people might still want single line-breaks
		// without breaking into a new paragraph.
		if ($line_breaks == true)
		    return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1</p><p>$2'), trim($string)).'</p>';
		else 
		    return '<p>'.preg_replace(
		    array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
		    array("</p>\n<p>", "</p>\n<p>", '$1</p><p>$2'), 
		    trim($string)).'</p>'; 
	}

	public function get_dynamic_sidebar($index = 1)
	{
		$sidebar_contents = "";
		ob_start();
		dynamic_sidebar($index);
		$sidebar_contents = ob_get_clean();
		return $sidebar_contents;
	}

	public function load_template_part($template_name, $part_name=null) {
	    ob_start();
	    get_template_part($template_name, $part_name);
	    $var = ob_get_contents();
	    ob_end_clean();
	    return $var;
	}
	/**
	 * Print HTML with meta information for the current post-date/time and author.
	 *
	 * @since 2.2.0
	 */
	public function posted_on() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . __( 'Sticky', 'odin' ) . ' </span>';
		}

		// Set up and print post meta information.
		printf( '<span class="entry-date"><i class="fa fa-clock-o"></i> <time class="entry-date" datetime="%s">%s</time></span> <span class="byline">%s <span class="author vcard">%s</span>.</span>',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( 'd M, Y' ) ),
			__( 'por', 'laf' ),
			get_the_author()
		);
	}

	public function is_post_type( $tipo ){
		global $wp_query;
		$retorna = ($tipo == get_post_type($wp_query->post->ID)) ? true : false;
		return $retorna;
	}

	public function get_all_gateways() {
		$payments = WC()->payment_gateways->get_available_payment_gateways();
		$return = [];
			foreach ( $payments as $payment ) :
				$return[] = $payment->title;
			endforeach;
		return $return;
	}

	public function google_analytics( $id ) {
		?>

		<!-- Google Analytics -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', '<?php echo $id; ?>', 'auto');
			ga('send', 'pageview');
		</script>
		<?php
	}

	public function rd_station_analytics( $src ) {
		?>
		
		<!-- RD Station -->
		<script type="text/javascript" async src="<?php echo $src; ?>"></script>
		<?php
	}

}