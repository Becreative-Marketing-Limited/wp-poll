<?php
/**
 * Template - Single Poll - Content
 *
 * @package single-poll/content
 * @author Liquidpoll
 */

defined( 'ABSPATH' ) || exit;

global $poll;


if ( $poll->has_content() ) : ?>

<div class="liquidpoll-content">
		<?php echo apply_filters( 'the_content', wp_kses_post( $poll->get_content() ) ); ?>
    </div>

	<?php liquidpoll_apply_css( '.liquidpoll-content', $poll->get_css_args( '_typography_content' ) ); ?>

<?php endif; ?>

