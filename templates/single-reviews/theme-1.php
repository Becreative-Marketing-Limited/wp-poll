<?php
/**
 * Single Review - Title
 */

defined( 'ABSPATH' ) || exit;

global $poll, $liquidpoll, $current_user;

?>

<div class="liquidpoll-reviews-rating">
    <div class="user-meta">
        <div class="avatar">
            <img src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
        </div>
        <div class="user">
            <span class="user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
            <span class="user-reviews-count">47 Reviews</span>
        </div>
    </div>
	<?php echo liquidpoll_get_review_stars(); ?>
    <p class="rating-label">Leave a review</p>
</div>

<div class="liquidpoll-reviews-stat">

</div>

<div class="liquidpoll-reviews-filter">

</div>


<div class="liquidpoll-reviews-items">

    <div class="liquidpoll-reviews-item">

    </div>

</div>
