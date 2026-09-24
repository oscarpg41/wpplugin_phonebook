<?php
/**
 * Shortcode y renderizado público de la agenda telefónica.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'phonebook', 'pb_render_shortcode' );

/**
 * [phonebook columnas="2"]
 */
function pb_render_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'columnas' => 1,
		),
		$atts,
		'phonebook'
	);

	$columnas = max( 1, min( 4, absint( $atts['columnas'] ) ) );
	$phones   = pb_get_phones();

	if ( empty( $phones ) ) {
		return '<p class="pb-empty">' . esc_html__( 'Todavía no se ha publicado ningún teléfono.', 'phone-book' ) . '</p>';
	}

	pb_enqueue_public_assets();

	ob_start();
	include PB_PLUGIN_DIR . 'includes/views/public-list.php';
	return ob_get_clean();
}

/**
 * Carga los estilos públicos solo cuando se usa el shortcode.
 */
function pb_enqueue_public_assets() {
	wp_enqueue_style( 'pb-public', PB_PLUGIN_URL . 'assets/css/public.css', array(), PB_VERSION );
}

/**
 * Devuelve una versión del teléfono apta para un enlace tel: (solo dígitos y
 * un + inicial opcional). Si no queda nada utilizable, devuelve cadena vacía.
 */
function pb_tel_href( $phone ) {
	$phone = trim( (string) $phone );
	$plus  = ( '' !== $phone && '+' === $phone[0] ) ? '+' : '';
	$digits = preg_replace( '/\D+/', '', $phone );

	return '' !== $digits ? $plus . $digits : '';
}

/**
 * Divide una entrada de teléfono en los distintos números que contiene. Una
 * entrada puede agrupar varios números separados por saltos de línea; también
 * se admite <br> como separador para no perder los datos de versiones
 * anteriores del plugin. Devuelve un array de cadenas recortadas y sin vacíos.
 */
function pb_split_phone( $phone ) {
	$parts = preg_split( '/\r\n|\r|\n|<br\s*\/?>/i', (string) $phone );
	$parts = array_map( 'trim', $parts );

	return array_values( array_filter( $parts, 'strlen' ) );
}
