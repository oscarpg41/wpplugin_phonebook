<?php
/**
 * Plugin Name: [Óscar Pérez Gómez] Agenda Telefónica
 * Plugin URI: https://github.com/oscarpg41/wpplugin_phonebook
 * Description: Gestiona y muestra una agenda telefónica. Usa el shortcode [phonebook] para mostrarla en cualquier página o entrada.
 * Version: 2.0.0
 * Author: Óscar Pérez
 * Author URI: https://www.oscarperez.es/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: phone-book
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PB_VERSION', '2.0.0' );
define( 'PB_PLUGIN_FILE', __FILE__ );
define( 'PB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once PB_PLUGIN_DIR . 'includes/db.php';
require_once PB_PLUGIN_DIR . 'includes/admin.php';
require_once PB_PLUGIN_DIR . 'includes/public.php';

register_activation_hook( PB_PLUGIN_FILE, 'pb_activate' );

/**
 * Crea o actualiza la tabla de la agenda telefónica al activar el plugin.
 */
function pb_activate() {
	pb_create_table();
}
