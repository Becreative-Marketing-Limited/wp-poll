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
            <input type="checkbox" name="rating" value="5">
            <span class="liquidpoll-checkbox"></span>
            <span class="rating-value">5</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 75%"></span></span>
            <span class="rating-result-value">75%</span>
        </label>

        <label class="stat-filter-item">
            <input type="checkbox" name="rating" value="4">
            <span class="liquidpoll-checkbox"></span>
            <span class="rating-value">4</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 52%"></span></span>
            <span class="rating-result-value">52%</span>
        </label>

        <label class="stat-filter-item">
            <input type="checkbox" name="rating" value="3">
            <span class="liquidpoll-checkbox"></span>
            <span class="rating-value">3</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 5%"></span></span>
            <span class="rating-result-value">5%></span>
        </label>

        <label class="stat-filter-item">
            <input type="checkbox" name="rating" value="2">
            <span class="liquidpoll-checkbox"></span>
            <span class="rating-value">2</span>
            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                <use xlink:href="#star"></use>
            </svg>
            <span class="rating-value-bar"><span style="width: 1%"></span></span>
            <span class="rating-result-value"><1%></span>
        </label>

        <label class="stat-filter-item">
            <input type="checkbox" name="rating" value="1">
            <span class="liquidpoll-checkbox"></span>
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
        <div class="review-comment-heading">
            <h2 class="comment-heading">I’m impressed with the product and service!</h2>
        </div>
        <div class="review-comment">
            <p>Couldn’t have had a better experience than I have with SUNS lifestyle from start to finish fast and
                efficient and kept informed every step of the way.
                The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a
                fantastic job. Highly recommended.</p>
        </div>
        <hr class="liquidpoll-divider">
        <div class="review-share-wrap">
            <div class="review-share">
                <button class="useful">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 17V8.2M1 9.8V15.4C1 16.2837 1.71634 17 2.6 17H13.341C14.5256 17 15.533 16.1357 15.7131 14.9649L16.5746 9.36494C16.7983 7.91112 15.6735 6.6 14.2025 6.6H11.4C10.9582 6.6 10.6 6.24183 10.6 5.8V2.97267C10.6 1.8832 9.7168 1 8.62733 1C8.36747 1 8.13198 1.15304 8.02644 1.3905L5.21115 7.72491C5.08275 8.01381 4.79625 8.2 4.4801 8.2H2.6C1.71634 8.2 1 8.91634 1 9.8Z"
                              stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Useful</span>
                </button>
                <button class="share">
                    <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.472 10.208L10.936 13.392M10.928 4.608L5.472 7.792M15.4 3.4C15.4 4.72548 14.3255 5.8 13 5.8C11.6745 5.8 10.6 4.72548 10.6 3.4C10.6 2.07452 11.6745 1 13 1C14.3255 1 15.4 2.07452 15.4 3.4ZM5.8 9C5.8 10.3255 4.72548 11.4 3.4 11.4C2.07452 11.4 1 10.3255 1 9C1 7.67452 2.07452 6.6 3.4 6.6C4.72548 6.6 5.8 7.67452 5.8 9ZM15.4 14.6C15.4 15.9255 14.3255 17 13 17C11.6745 17 10.6 15.9255 10.6 14.6C10.6 13.2745 11.6745 12.2 13 12.2C14.3255 12.2 15.4 13.2745 15.4 14.6Z"
                              stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Share</span>
                </button>
            </div>
            <div class="review-report">
                <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 11.4C1 11.4 1.8 10.6 4.2 10.6C6.6 10.6 8.2 12.2 10.6 12.2C13 12.2 13.8 11.4 13.8 11.4V1.8C13.8 1.8 13 2.6 10.6 2.6C8.2 2.6 6.6 1 4.2 1C1.8 1 1 1.8 1 1.8L1 17"
                          stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="review-replay">

            <div class="replay-info-wrap">
                <div class="replay-info">
                    <div class="replay-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2V3.4C2 6.76031 2 8.44047 2.65396 9.72394C3.2292 10.8529 4.14708 11.7708 5.27606 12.346C6.55953 13 8.23969 13 11.6 13H18M18 13L13 8M18 13L13 18"
                                  stroke="#5F64EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="service-logo">
                        <img src="">
                    </div>
                    <div class="service-info">
                        <span class="service-name">Liquid Poll Pro</span>
                        <span>replied</span>
                    </div>
                </div>
                <p class="reply-date">February 25, 2023</p>
            </div>
            <div class="replay">
                <p>Thanks so much for your review! It means a lot to know you'd happily recommend us and we're delighted
                to hear you were happy with the installation service and product received. Enjoy your product!</p>
            </div>

        </div>

    </div>

</div>
