<?php

namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$sections = array(
	array(
		'id'    => JM_TC_SLUG_MAIN_OPTION,
		'title' => __( 'Options', 'jm-tc' )
	),
	array(
		'id'    => JM_TC_SLUG_CPT_OPTION,
		'title' => __( 'Post types' )
	),
);
