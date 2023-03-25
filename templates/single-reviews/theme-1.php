<?php
/**
 * Single Review - Title
 */

defined( 'ABSPATH' ) || exit;

global $poll, $liquidpoll, $current_user, $wp_query;

$rating_selected       = isset( $_GET['rating'] ) ? sanitize_text_field( $_GET['rating'] ) : '';
$class_reviews_form    = '';
$class_reviews_listing = 'active';

if ( ! empty( $rating_selected ) && $rating_selected > 1 && $rating_selected <= 5 ) {
	$class_reviews_form    = 'active';
	$class_reviews_listing = '';
}

//echo "<pre>";
//print_r( wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) ) );
//echo "</pre>";

?>


<form class="reviews-form <?php echo esc_attr( $class_reviews_form ); ?>">
    <div class="service">
        <div class="icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 8H1M1 8L8 15M1 8L8 1" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="service-logo">
            <img src="<?php echo esc_url(LIQUIDPOLL_PLUGIN_URL . 'assets/images/service-logo.svg') ?>" alt="service">
        </div>
        <div class="service-info">
            <span class="service-name">Liquid Poll Pro</span>
            <span><a href="#">liquidpoll.com</a></span>
        </div>
    </div>
    <hr class="liquidpoll-divider">
    <div class="rating-selected">
        <div class="form-group rating-label">
            <label class="rating-header">Rate your experience</label>
        </div>
    </div>

    <div class="form-group review-title">
        <label for="title" class="review-title-label">Give your review a title</label>
        <input type="text" id="title" name="title" placeholder="Impressed with the service!">
    </div>

    <div class="form-group review-textarea">
        <label for="review" class="review-label">Briefly tell us about your experience</label>
        <textarea id="review" name="review"></textarea>
    </div>

    <div class="form-group experience-date">
        <label for="experience-time" class="review-label">When did you have this experience?</label>
        <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 9H1M14 1V5M6 1V5M5.8 21H14.2C15.8802 21 16.7202 21 17.362 20.673C17.9265 20.3854 18.3854 19.9265 18.673 19.362C19 18.7202 19 17.8802 19 16.2V7.8C19 6.11984 19 5.27976 18.673 4.63803C18.3854 4.07354 17.9265 3.6146 17.362 3.32698C16.7202 3 15.8802 3 14.2 3H5.8C4.11984 3 3.27976 3 2.63803 3.32698C2.07354 3.6146 1.6146 4.07354 1.32698 4.63803C1 5.27976 1 6.11984 1 7.8V16.2C1 17.8802 1 18.7202 1.32698 19.362C1.6146 19.9265 2.07354 20.3854 2.63803 20.673C3.27976 21 4.11984 21 5.8 21Z"
                  stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input type="text" id="experience-time" name="experience-time">
    </div>

    <div class="consent">
        <label class="consent-items" for="consent">
            <input type="checkbox" id="consent" name="consent">
            <span class="liquidpoll-checkbox"></span>
            <span class="consent-desc">I confirm this review about my own genuine experience. I am eligible to leave this review, and have not been offered any incentive or payment to leave a review for this company</span>
        </label>
    </div>
    <hr class="liquidpoll-divider">
    <div class="submit-section">
        <img class="user-logo" src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
        <p class="user-name"><?php echo esc_html( $current_user->display_name ); ?></p>

        <button type="submit" class="review-submit">Submit your review</button>
    </div>
</form>

<div class="reviews-listing <?php echo esc_attr( $class_reviews_listing ); ?>">

    <form action="" method="get" class="liquidpoll-reviews-rating liquidpoll-review-box">
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
    </form>

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
            <div class="liquidpoll-relevant-wrap">
                <div class="relevant-items">
                    <label class="relevant-item">
                        <input type="radio" name="relevant" value="most-relevant">
                        <span class="liquidpoll-checkbox"></span>
                        <span>Most Relevant</span>
                    </label>
                    <label class="relevant-item">
                        <input type="radio" name="relevant" value="newest">
                        <span class="liquidpoll-checkbox"></span>
                        <span>Newest</span>
                    </label>
                    <label class="relevant-item">
                        <input type="radio" name="relevant" value="oldest">
                        <span class="liquidpoll-checkbox"></span>
                        <span>Oldest</span>
                    </label>
                </div>
            </div>
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
                            <img src="<?php echo esc_url(LIQUIDPOLL_PLUGIN_URL . 'assets/images/service-logo.svg') ?>" alt="service-logo">
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

    <div class="liquidpoll-filter-modal-wrap">

        <div class="liquidpoll-filter-modal">
            <div class="modal-heading">
                <h2>Filter by</h2>
                <div class="close-button">
                    <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24.75 14.25L14.25 24.75M14.25 14.25L24.75 24.75M37 19.5C37 29.165 29.165 37 19.5 37C9.83502 37 2 29.165 2 19.5C2 9.83502 9.83502 2 19.5 2C29.165 2 37 9.83502 37 19.5Z"
                              stroke="#9397EC" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <hr class="liquidpoll-divider">

            <div class="filter-rating">
                <span>Rating</span>
                <div class="filter-stars">
                    <div>
                        <input type="checkbox" id="rating-1" name="rating-filter" value="1">
                        <label class="filter-star" for="rating-1">
                            <span>1</span>
                            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                                <use xlink:href="#star"></use>
                            </svg>
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="rating-2" name="rating-filter" value="2">
                        <label class="filter-star" for="rating-2">
                            <span>2</span>
                            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                                <use xlink:href="#star"></use>
                            </svg>
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="rating-3" name="rating-filter" value="3">
                        <label class="filter-star" for="rating-3">
                            <span>3</span>
                            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                                <use xlink:href="#star"></use>
                            </svg>
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="rating-4" name="rating-filter" value="4">
                        <label class="filter-star" for="rating-4">
                            <span>4</span>
                            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                                <use xlink:href="#star"></use>
                            </svg>
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="rating-5" name="rating-filter" value="5">
                        <label class="filter-star" for="rating-5">
                            <span>5</span>
                            <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                                <use xlink:href="#star"></use>
                            </svg>
                        </label>
                    </div>
                </div>
            </div>

            <div class="filter-date">
                <span>Date posted</span>
                <div class="filter-date-items">
                    <label class="date-item">
                        <input type="radio" name="filter-date" value="all">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">All reviews</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter-date" value="30">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 30 days</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter-date" value="3">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 3 months</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter-date" value="6">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 6 months</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter-date" value="12">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 12 months</span>
                    </label>
                </div>
            </div>

            <hr class="liquidpoll-divider">

            <div class="filter-footer">
                <button class="button-rest">Reset</button>
                <button class="button-filter">Filter</button>
            </div>

        </div>

    </div>

</div>