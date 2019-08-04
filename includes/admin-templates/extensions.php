<?php
/**
 * Admin Template: Extensions
 *
 * @package includes/admin-templates/extensions.php
 * @author Pluginbazar
 */

$extensions = array();
$response   = wp_remote_get( 'https://api.pluginbazar.com/wp-json/wpp/v1/get-addons' );

if ( ! is_wp_error( $response ) ) {
	$response   = wp_remote_retrieve_body( $response );
	$extensions = json_decode( $response, true );
} else {
	printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', $response->get_error_message() );
}


?>

<div class="wpp-extensions">

	<?php foreach ( $extensions as $extension ) :

		$title = isset( $extension['title'] ) ? $extension['title'] : '';
		$thumb = isset( $extension['thumb'] ) ? $extension['thumb'] : '';
		$desc = isset( $extension['desc'] ) ? $extension['desc'] : '';
		$pricing = isset( $extension['pricing'] ) ? $extension['pricing'] : '';
		$url = isset( $extension['url'] ) ? $extension['url'] : '';
		$purchase_url = isset( $extension['purchase_url'] ) ? $extension['purchase_url'] : '';

		?>

        <div class="wpp-extension">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>">
            <div class="info">
                <h2><?php echo esc_html( $title ); ?></h2>
                <p><?php echo esc_html( $desc ); ?></p>
                <div class="pricing">
                    <a class="price" target="_blank"
                       href="<?php echo esc_url( $purchase_url ); ?>"><?php echo esc_html( $pricing ); ?></a>
                    <a class="purchase" target="_blank"
                       href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'View Details', 'wp-poll' ); ?></a>
                </div>
            </div>
        </div>

	<?php endforeach; ?>

</div>
