<?php
/**
 * Admin Template: Extensions
 *
 * @package includes/admin-templates/extensions.php
 * @author Pluginbazar
 */

$extensions = array();
$response   = wp_remote_get( esc_url( 'https://api.pluginbazar.com/wp-json/wpp/get-addons' ) );

if ( ! is_wp_error( $response ) ) {
	$response   = wp_remote_retrieve_body( $response );
	$extensions = json_decode( $response, true );
} else {
	printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', $response->get_error_message() );
}

?>

<div class="pb-extensions">

	<?php foreach ( $extensions as $extension ) :

		$title = isset( $extension['title'] ) ? $extension['title'] : '';
		$thumb = isset( $extension['thumb'] ) ? $extension['thumb'] : '';
		$desc = isset( $extension['desc'] ) ? $extension['desc'] : '';
		$pricing = isset( $extension['pricing'] ) ? $extension['pricing'] : '';
		$url = isset( $extension['url'] ) ? $extension['url'] : '';
		$purchase_url = isset( $extension['purchase_url'] ) ? $extension['purchase_url'] : '';

		?>

        <div class="pb-extension">
            <a href="<?php echo esc_url( $url ); ?>"><img src="<?php echo esc_url( $thumb ); ?>"
                                                          alt="<?php echo esc_attr( $title ); ?>"></a>
            <div class="info">
                <h2><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></h2>
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
