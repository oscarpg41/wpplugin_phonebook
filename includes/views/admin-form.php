<?php
/**
 * Formulario de alta/edición de teléfono.
 * Variables disponibles: $values, $title
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap pb-admin-wrap">
	<h1><?php echo esc_html( $title ); ?></h1>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=opg_phonebook' ) ); ?>" id="pbAdminForm">
		<?php wp_nonce_field( 'pb_save_phone', 'pb_nonce' ); ?>
		<input type="hidden" name="pb_action" value="save">
		<input type="hidden" name="idPhone" value="<?php echo esc_attr( $values['id'] ); ?>">

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="name"><?php esc_html_e( 'Nombre', 'phone-book' ); ?></label></th>
					<td>
						<input type="text" name="name" id="name" class="regular-text" required maxlength="255"
							placeholder="<?php esc_attr_e( 'Introduzca el nombre', 'phone-book' ); ?>"
							value="<?php echo esc_attr( $values['name'] ); ?>">
					</td>
				</tr>
				<tr>
					<th><label for="phone"><?php esc_html_e( 'Teléfono', 'phone-book' ); ?></label></th>
					<td>
						<textarea name="phone" id="phone" class="regular-text" rows="4" required
							placeholder="<?php esc_attr_e( 'Introduzca el teléfono', 'phone-book' ); ?>"><?php echo esc_textarea( implode( "\n", pb_split_phone( $values['phone'] ) ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Puede indicar varios números, uno por línea. Cada uno se mostrará en su propia línea con un enlace para llamar.', 'phone-book' ); ?></p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center; padding-top: 20px;">
						<input type="submit" class="button button-primary button-hero" value="<?php esc_attr_e( 'Enviar', 'phone-book' ); ?>">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
