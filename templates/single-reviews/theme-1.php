<?php
/**
 * Single Review - Title
 */

defined( 'ABSPATH' ) || exit;

global $poll, $liquidpoll, $current_user;

?>

<div class="liquidpoll-reviews-rating liquidpoll-review-box">
    <div class="user-meta">
        <div class="avatar">
            <img src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
        </div>
        <div class="user">
            <span class="user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
            <span class="user-reviews-count">47 Reviews</span>
        </div>
    </div>

    <div class="review-icons">
		<?php echo liquidpoll_get_review_stars(); ?>
        <p class="rating-label">Leave a review</p>
    </div>
</div>

<div class="liquidpoll-reviews-stat liquidpoll-review-box">
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

    <div class="review-stat-filter">

        <label class="stat-filter-item">
            <input type="radio" name="rating" value="5">
            <span class="rating-value">5</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 75%"></span></span>
            <span class="rating-result-value">75%</span>
        </label>

        <label class="stat-filter-item">
            <input type="radio" name="rating" value="4">
            <span class="rating-value">4</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 52%"></span></span>
            <span class="rating-result-value">52%</span>
        </label>

        <label class="stat-filter-item">
            <input type="radio" name="rating" value="3">
            <span class="rating-value">3</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 5%"></span></span>
            <span class="rating-result-value">5%></span>
        </label>

        <label class="stat-filter-item">
            <input type="radio" name="rating" value="2">
            <span class="rating-value">2</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 1%"></span></span>
            <span class="rating-result-value"><1%></span>
        </label>

        <label class="stat-filter-item">
            <input type="radio" name="rating" value="1">
            <span class="rating-value">1</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 1%"></span></span>
            <span class="rating-result-value"><1%></span>
        </label>
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


    <div class="liquidpoll-reviews-item liquidpoll-review-box">
        <div class="review-box-heading">
            <div class="user-details">
                <div class="user-avatar">
                    <img src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
                </div>
                <div class="user-stat">
                    <p class="user-name"><?php echo esc_html( $current_user->display_name ); ?> <span class="location">New York, US</span></p>
                    <p class="user-reviews-count">47 Reviews</p>
                </div>
            </div>
            <div class="review-published">
                <p class="published-time"><strong>Posted</strong> a day ago</p>
                <p class="experienced-time"><strong>Experienced</strong> August 12, 2022</p>
            </div>
        </div>
        <div class="review-stars">
			<?php echo liquidpoll_get_review_stars( 3.5 ); ?>
        </div>
    </div>

</div>
