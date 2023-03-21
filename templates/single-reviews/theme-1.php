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
    <div class="review-stat-heading">
        <div class="stat-heading">
            <h2>Reviews</h2>
            <span>225 ratings</span>
        </div>
        <div class="stat-rating">
            <div class="star">
                <svg role="img" aria-label="rating">
                    <use xlink:href="#star"></use>
                </svg>
            </div>
            <span class="stat"><span class="rating">4.9</span>out of 5</span>
        </div>
    </div>
</div>

<div class="liquidpoll-reviews-filter">
    <div class="reviews-filter">
        <span class="filter">Filter</span>
        <svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 5L14 5M14 5C14 6.65686 15.3431 8 17 8C18.6569 8 20 6.65685 20 5C20 3.34315 18.6569 2 17 2C15.3431 2 14 3.34315 14 5ZM8 13L20 13M8 13C8 14.6569 6.65685 16 5 16C3.34315 16 2 14.6569 2 13C2 11.3431 3.34315 10 5 10C6.65685 10 8 11.3431 8 13Z"
                  stroke="#5F64EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="reviews-relevant">
        <span class="filter">Most relevant</span>
        <svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.33333 8.66667H18.6667M2 2H22M8.66667 15.3333H15.3333" stroke="#5F64EB" stroke-width="3"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</div>


<div class="liquidpoll-reviews-items">
    <div class="reviews-items-heading">
        <div class="user-meta">
            <div class="avatar">
                <img src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>"
                     alt="<?php echo esc_attr( 'poller' ); ?>">
            </div>
            <div class="user">
                <span class="user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
                <span class="user-reviews-count">47 Reviews</span>
            </div>
        </div>
    </div>
    <div class="liquidpoll-reviews-item">

    </div>

</div>
