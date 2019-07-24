<?php
/**
 * Template - Archive - thumb
 *
 * @package loop/thumb
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>

<?php if( $poll->has_thumbnail() && wpp()->display_on_archive( 'thumb' ) ) : ?>

    <div class="poll-thumb">
        <a href="<?php echo esc_url( $poll->get_permalink() ); ?>"><img
                                                                        src="<?php echo esc_url( $poll->get_thumbnail('thumbnail' ) ); ?>"
                                                                        alt="<?php echo esc_attr( $poll->get_name() ); ?>"></a>
    </div>

<?php endif; ?>