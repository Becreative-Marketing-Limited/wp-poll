<?php
/**
 * Admin Template: Poll Submitbox
 *
 * @package includes/admin-templates/poll-submitbox
 * @author Pluginbazar
 */

global $post;

if ( $post->post_type != 'poll' ) {
	return;
}

?>

<div class="wpp-poll-publishbox">
    <div class="wpp-item results">
        <label><?php esc_html_e( 'Results / Report', 'wp-poll' ); ?></label>
        <a class="tt--hint tt--top"
           href="<?php echo esc_url( admin_url( 'edit.php?post_type=poll&page=wpp-settings&tab=wpp-reports&poll-id=' . $post->ID ) ); ?>"
           aria-label="<?php esc_attr_e( 'Click to see Results', 'wp-poll' ); ?>">
			<?php esc_html_e( 'View Results', 'wp-poll' ); ?>
        </a>
    </div>
    <div class="wpp-item">
        <label><?php esc_html_e( 'Shortcode', 'wp-poll' ); ?></label>
        <span class="shortcode tt--hint tt--top"
              aria-label="<?php esc_attr_e( 'Click to Copy', 'wp-poll' ); ?>"><?php printf( '[poll id="%s"]', $post->ID ); ?></span>
    </div>
</div>

<style>
    #submitpost #minor-publishing-actions,
    #submitpost #misc-publishing-actions .misc-pub-section::before,
    #submitpost #misc-publishing-actions .misc-pub-section #timestamp::before {
        display: none !important;
    }

    #submitpost .misc-pub-section {
        padding: 20px 10px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #f1f1f1;
    }

    #submitpost #major-publishing-actions {
        background: #fff;
        padding: 20px 10px;
        border-top: none;
    }

    #submitpost .wpp-poll-publishbox {
        margin: -10px -10px 20px;
        padding: 10px 10px 20px;
        clear: both;
        display: block;
        border-bottom: 1px solid #ddd;
    }

    #submitpost .wpp-item {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    #submitpost .wpp-item span.shortcode {
        background: #f1f1f1;
        border: 1px solid #a2a2a2;
        padding: 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 13px;
        color: #868686;
        /*user-select: none;*/
        /*-webkit-user-select: none;*/
        /*-webkit-user-modify: read-only;*/
    }

    .wpp-item.results {
        padding: 0 10px 20px;
        border-bottom: 1px solid #f1f1f1;
        margin: 0 -10px 20px -10px;
    }

</style>

<script>

</script>