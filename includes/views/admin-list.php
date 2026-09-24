<?php
/**
 * Listado de teléfonos en el panel de administración.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phones = pb_get_phones();
?>
<div class="wrap pb-admin-wrap">
	<hr>
	<h2><?php esc_html_e( 'Directorio telefónico', 'phone-book' ); ?></h2>

	<?php if ( empty( $phones ) ) : ?>
		<p><?php esc_html_e( 'Todavía no se ha añadido ningún teléfono.', 'phone-book' ); ?></p>
	<?php else : ?>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Nombre', 'phone-book' ); ?></th>
					<th><?php esc_html_e( 'Teléfono', 'phone-book' ); ?></th>
					<th style="width:220px"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $phones as $phone ) : ?>
				<tr>
					<td><?php echo esc_html( $phone->name ); ?></td>
					<td><?php echo implode( '<br>', array_map( 'esc_html', pb_split_phone( $phone->phone ) ) ); ?></td>
					<td>
						<a class="button button-small" href="<?php echo esc_url( admin_url( 'admin.php?page=opg_phonebook&task=edit_phone&id=' . $phone->idPhone ) ); ?>">
							<span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Modificar', 'phone-book' ); ?>
						</a>
						<a class="button button-small pb-delete-link" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=opg_phonebook&task=remove_phone&id=' . $phone->idPhone ), 'pb_delete_phone_' . $phone->idPhone ) ); ?>">
							<span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Borrar', 'phone-book' ); ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
