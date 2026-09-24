<?php
/**
 * Pantalla de administración: alta, edición, borrado y listado de teléfonos.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'pb_register_admin_menu' );

/**
 * Registra la página de administración dentro del menú compartido
 * "Oscar Pérez Plugins". Si el plugin maestro (weblinks) ya está presente,
 * se añade como submenú suyo; si no, se crea el menú compartido aquí.
 */
function pb_register_admin_menu() {
	if ( ! function_exists( 'opg_plugin_links_show_form_in_wpadmin' ) ) {
		add_menu_page( 'Oscar Pérez Plugins', 'Oscar Pérez Plugins', 'manage_options', 'opg_plugins', 'pb_render_admin_page', 'dashicons-admin-links', 110 );
		remove_submenu_page( 'opg_plugins', 'opg_plugins' );
	}

	add_submenu_page(
		'opg_plugins',
		__( 'Agenda telefónica', 'phone-book' ),
		__( 'Agenda telefónica', 'phone-book' ),
		'manage_options',
		'opg_phonebook',
		'pb_render_admin_page'
	);
}

add_action( 'admin_enqueue_scripts', 'pb_admin_enqueue_scripts' );

/**
 * Carga el script de administración solo en nuestra pantalla.
 */
function pb_admin_enqueue_scripts( $hook_suffix ) {
	if ( false === strpos( (string) $hook_suffix, 'opg_phonebook' ) ) {
		return;
	}

	wp_enqueue_script( 'pb-admin', PB_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), PB_VERSION, true );
	wp_localize_script(
		'pb-admin',
		'pbAdmin',
		array(
			'confirmDelete' => __( '¿Está seguro de eliminar este teléfono?', 'phone-book' ),
		)
	);
}

/**
 * Procesa las acciones (guardar/editar/borrar) y pinta el formulario y el listado.
 */
function pb_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'No tiene permisos suficientes para acceder a esta página.', 'phone-book' ) );
	}

	$values = array(
		'id'    => 0,
		'name'  => '',
		'phone' => '',
	);

	if ( isset( $_POST['pb_action'] ) && 'save' === $_POST['pb_action'] ) {
		check_admin_referer( 'pb_save_phone', 'pb_nonce' );

		$data = array(
			'name'  => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
			'phone' => isset( $_POST['phone'] ) ? pb_sanitize_phone( wp_unslash( $_POST['phone'] ) ) : '',
		);

		$id = isset( $_POST['idPhone'] ) ? absint( $_POST['idPhone'] ) : 0;

		if ( '' === $data['name'] || '' === $data['phone'] ) {
			echo '<div class="error notice"><p>' . esc_html__( 'El nombre y el teléfono son obligatorios.', 'phone-book' ) . '</p></div>';
		} elseif ( $id > 0 ) {
			pb_update_phone( $id, $data );
			echo '<div class="updated notice"><p>' . esc_html__( 'Teléfono modificado correctamente.', 'phone-book' ) . '</p></div>';
		} else {
			pb_save_phone( $data );
			echo '<div class="updated notice"><p>' . esc_html__( 'Información del teléfono guardada correctamente.', 'phone-book' ) . '</p></div>';
		}
	} elseif ( isset( $_GET['task'], $_GET['id'] ) && 'edit_phone' === $_GET['task'] ) {
		$id  = absint( $_GET['id'] );
		$row = pb_get_phone( $id );

		if ( $row ) {
			$values = array(
				'id'    => $id,
				'name'  => $row->name,
				'phone' => $row->phone,
			);
		}
	} elseif ( isset( $_GET['task'], $_GET['id'] ) && 'remove_phone' === $_GET['task'] ) {
		$id = absint( $_GET['id'] );
		check_admin_referer( 'pb_delete_phone_' . $id );
		pb_delete_phone( $id );
		echo '<div class="updated notice"><p>' . esc_html__( 'Se ha borrado la información del teléfono.', 'phone-book' ) . '</p></div>';
	}

	$title = $values['id'] > 0
		? __( 'Modificar información del teléfono', 'phone-book' )
		: __( 'Añadir un nuevo teléfono', 'phone-book' );

	include PB_PLUGIN_DIR . 'includes/views/admin-form.php';
	include PB_PLUGIN_DIR . 'includes/views/admin-list.php';
}

/**
 * Sanea la entrada de teléfonos. Una entrada puede contener varios números
 * (uno por línea en el formulario). Se limpia cada número por separado, se
 * descartan los vacíos y se almacenan separados por saltos de línea.
 */
function pb_sanitize_phone( $phone ) {
	$parts = array_map( 'sanitize_text_field', pb_split_phone( $phone ) );
	$parts = array_filter( $parts, 'strlen' );

	return implode( "\n", $parts );
}
