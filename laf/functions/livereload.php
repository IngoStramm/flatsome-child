<?php
// add_action( 'wp_enqueue_scripts', 'cf_livereload' );

function cf_livereload() {
	wp_enqueue_script( 'cf-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true );
}

