<?php
/**
 * Metabox Template
 */

if ( ! $meta_box instanceof WPP_Poll_meta ) {
	return;
}

global $post;

//echo '<pre>'; print_r( $post ); echo '</pre>';


wpp()->PB_Settings()->render_panel( array(
	'logo_url'    => esc_url( WPP_PLUGIN_URL . 'assets/images/wp-poll.svg' ),
	'items'       => array(
		array(
			'id'         => 'general',
			'label'      => esc_html__( 'General', 'wp-poll' ),
			'page_title' => esc_html__( 'Poll General Data', 'wp-poll' ),
			'fields'     => wpp()->get_poll_meta_fields( 'general' ),
		),
		'options' => array(
			'id'         => 'options',
			'label'      => esc_html__( 'Options', 'wp-poll' ),
			'page_title' => esc_html__( 'Poll Options Data', 'wp-poll' ),
			'fields'     => wpp()->get_poll_meta_fields( 'options' ),
		),
		array(
			'id'         => 'designs',
			'label'      => esc_html__( 'Designs', 'wp-poll' ),
			'page_title' => esc_html__( 'Poll Designs', 'wp-poll' ),
			'fields'     => wpp()->get_poll_meta_fields( 'designs' ),
		),
		array(
			'id'         => 'settings',
			'label'      => esc_html__( 'Settings', 'wp-poll' ),
			'page_title' => esc_html__( 'Poll Settings', 'wp-poll' ),
			'fields'     => wpp()->get_poll_meta_fields( 'settings' ),
		),
		array(
			'id'         => 'results',
			'label'      => esc_html__( 'Results', 'wp-poll' ),
			'page_title' => esc_html__( 'Poll Results', 'wp-poll' ),
			'fields'     => '',
		),
		array(
			'id'         => 'edit_results',
			'label'      => esc_html__( 'Manipulate Results', 'wp-poll' ),
			'page_title' => esc_html__( 'Update Poll Results', 'wp-poll' ),
			'fields'     => '',
		),
	),
	'footer_menu' => array(
		array(
			'label' => esc_html__( 'Ticket', 'wp-poll' ),
			'url'   => PB_TICKET_URL,
		),
		array(
			'label' => esc_html__( 'Docs', 'wp-poll' ),
			'url'   => WPP_DOCS_URL,
		),
		array(
			'label' => esc_html__( 'Get Pro', 'wp-poll' ),
			'url'   => WPP_PLUGIN_LINK,
		),
	),
	'post_id'     => $post->ID,
) );

return;
?>

<div class="wpp-poll-meta">
    <div class="meta-sidebar">
        <div class="header"><img src="<?php echo esc_url( WPP_PLUGIN_URL . 'assets/images/wp-poll.svg' ); ?>" alt="<?php esc_html_e( 'Poll Logo', 'wp-poll' ); ?>"></div>
        <ul class="meta-nav">
            <li class="active" data-target="tab-content-general"><?php esc_html_e( 'General Data', 'wp-poll' ); ?></li>
            <li data-target="tab-content-options"><?php esc_html_e( 'Options', 'wp-poll' ); ?></li>
            <li data-target="tab-content-designs"><?php esc_html_e( 'Designs', 'wp-poll' ); ?></li>
            <li data-target="tab-content-settings"><?php esc_html_e( 'Settings', 'wp-poll' ); ?></li>
            <li data-target="tab-content-results"><?php esc_html_e( 'Results', 'wp-poll' ); ?></li>
            <li data-target="tab-content-manipulate"><?php esc_html_e( 'Manipulate Results', 'wp-poll' ); ?></li>
        </ul>
        <div class="footer">
            <a class="footer-link" href="<?php echo esc_url( PB_TICKET_URL ); ?>" target="_blank"><?php esc_html_e( 'Ticket', 'wp-poll' ); ?></a>
            <a class="footer-link" href="<?php echo esc_url( WPP_DOCS_URL ); ?>" target="_blank"><?php esc_html_e( 'Doc', 'wp-poll' ); ?></a>
            <a class="footer-link" href="<?php echo esc_url( WPP_PLUGIN_LINK ); ?>" target="_blank"><?php esc_html_e( 'Get Pro', 'wp-poll' ); ?></a>
        </div>
    </div>

    <div class="meta-content">
        <div class="tab-content-item tab-content-general active">
            <p class="item-title">Poll General Data</p>
            <div class="item-wrap">
				<?php wpp()->PB_Settings()->generate_fields( $post->ID ); ?>
            </div>
        </div>

        <div class="tab-content-item tab-content-options">
            <p class="item-title">Poll Options Data</p>
            <div class="item-wrap">
				<?php wpp()->PB_Settings()->generate_fields( $meta_box->get_meta_fields( 'options' ), $post->ID ); ?>
            </div>
        </div>

        <div class="tab-content-item tab-content-designs">
            <p class="item-title">Poll Designs</p>
            <div class="item-wrap">
				<?php wpp()->PB_Settings()->generate_fields( $meta_box->get_meta_fields( 'designs' ), $post->ID ); ?>
            </div>
        </div>

        <div class="tab-content-item tab-content-settings">
            <p class="item-title">Poll Settings</p>
            <div class="item-wrap">
				<?php wpp()->PB_Settings()->generate_fields( $meta_box->get_meta_fields( 'settings' ), $post->ID ); ?>
            </div>
        </div>

        <div class="tab-content-item tab-content-results">
            <p class="item-title">Poll Results</p>
            <div class="item-wrap">

            </div>
        </div>

        <div class="tab-content-item tab-content-manipulate">
            <p class="item-title">Manipulate Poll Results</p>
            <div class="item-wrap">

            </div>
        </div>
    </div>
</div>
