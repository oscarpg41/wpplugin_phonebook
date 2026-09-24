<?php
/**
 * Listado público (shortcode [phonebook]).
 * Variables disponibles: $phones, $columnas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="pb-list" style="--pb-columnas: <?php echo esc_attr( $columnas ); ?>;">
	<?php foreach ( $phones as $phone ) : ?>
		<?php $numbers = pb_split_phone( $phone->phone ); ?>
		<li class="pb-item">
			<span class="pb-name"><?php echo esc_html( $phone->name ); ?></span>
			<span class="pb-numbers">
				<?php foreach ( $numbers as $number ) : ?>
					<?php $tel = pb_tel_href( $number ); ?>
					<?php if ( '' !== $tel ) : ?>
						<a class="pb-phone" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $number ); ?></a>
					<?php else : ?>
						<span class="pb-phone"><?php echo esc_html( $number ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</span>
		</li>
	<?php endforeach; ?>
</ul>
