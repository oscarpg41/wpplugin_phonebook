<?php
/**
 * Acceso a base de datos para la tabla de la agenda telefónica.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nombre completo (con prefijo) de la tabla de teléfonos.
 * Se mantiene el nombre histórico `opg_plugin_phonebook` para no perder los datos
 * de instalaciones que ya usaban versiones anteriores del plugin.
 */
function pb_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'opg_plugin_phonebook';
}

/**
 * Crea o actualiza la tabla mediante dbDelta (no destruye datos existentes).
 */
function pb_create_table() {
	global $wpdb;

	$table_name      = pb_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table_name} (
		idPhone INT(11) NOT NULL AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		phone VARCHAR(255) NOT NULL,
		PRIMARY KEY  (idPhone),
		KEY name (name)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Inserta un nuevo teléfono. $data debe venir ya saneado.
 */
function pb_save_phone( $data ) {
	global $wpdb;

	return $wpdb->insert(
		pb_table_name(),
		array(
			'name'  => $data['name'],
			'phone' => $data['phone'],
		),
		array( '%s', '%s' )
	);
}

/**
 * Actualiza un teléfono existente.
 */
function pb_update_phone( $id, $data ) {
	global $wpdb;

	return $wpdb->update(
		pb_table_name(),
		array(
			'name'  => $data['name'],
			'phone' => $data['phone'],
		),
		array( 'idPhone' => absint( $id ) ),
		array( '%s', '%s' ),
		array( '%d' )
	);
}

/**
 * Elimina un teléfono por id.
 */
function pb_delete_phone( $id ) {
	global $wpdb;
	return $wpdb->delete( pb_table_name(), array( 'idPhone' => absint( $id ) ), array( '%d' ) );
}

/**
 * Recupera un teléfono por id.
 */
function pb_get_phone( $id ) {
	global $wpdb;
	$table = pb_table_name();

	return $wpdb->get_row(
		$wpdb->prepare(
			"SELECT idPhone, name, phone FROM {$table} WHERE idPhone = %d",
			absint( $id )
		)
	);
}

/**
 * Recupera todos los teléfonos, ordenados por nombre.
 */
function pb_get_phones() {
	global $wpdb;
	$table = pb_table_name();

	return $wpdb->get_results(
		"SELECT idPhone, name, phone FROM {$table} ORDER BY name ASC"
	);
}
