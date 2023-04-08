<?php
/**
 * Single Review - Title
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

global $poll, $liquidpoll, $current_user, $wp_query, $wpdb, $wp;

$login_url             = wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) );
$rating_selected       = isset( $_GET['rating'] ) ? sanitize_text_field( $_GET['rating'] ) : '';
$rating_filtered       = isset( $_GET['r'] ) ? sanitize_text_field( $_GET['r'] ) : '';
$relevant_orderby      = isset( $_GET['relevant'] ) ? sanitize_text_field( $_GET['relevant'] ) : '';
$filter_date           = isset( $_GET['filter_date'] ) ? sanitize_text_field( $_GET['filter_date'] ) : '';
$filter_by_ratings     = isset( $_GET['filter_rating'] ) ? $_GET['filter_rating'] : '';
$class_reviews_form    = '';
$class_reviews_listing = 'active';

if ( ! empty( $rating_selected ) && $rating_selected > 1 && $rating_selected <= 5 ) {
	$class_reviews_form    = 'active';
	$class_reviews_listing = '';
}

$service_logo            = Utils::get_meta( 'reviews_service_logo' );
$service_name            = Utils::get_meta( 'reviews_service_name' );
$service_url             = Utils::get_meta( 'reviews_service_url' );
$consent_required        = $poll->get_meta( 'is_consent_required', false ) ? 'required' : '';
$consent_desc            = Utils::get_meta( 'reviews_consent_desc' );
$all_reviews             = $poll->get_poll_results();
$all_reviews_rating      = array();
$all_reviews_value       = 0;
$poll_results_query_args = array(
	'rating'        => $rating_filtered,
	'relevant'      => $relevant_orderby,
	'filter_date'   => $filter_date,
	'filter_rating' => $filter_by_ratings,
);

foreach ( $all_reviews as $review ) {

	$polled_value = Utils::get_args_option( 'polled_value', $review );

	if ( ! empty( $polled_value ) && $polled_value >= 0 && $polled_value <= 5 ) {
		$polled_value = ( ( $polled_value / 0.5 ) % 2 !== 0 ) ? $polled_value + 0.5 : $polled_value;

		$all_reviews_value                   += $polled_value;
		$all_reviews_rating[ $polled_value ] = ( $all_reviews_rating[ $polled_value ] ?? 0 ) + 1;
	}
}

$overall_rating = round( $all_reviews_value / count( $all_reviews ), 1 );

?>

<form method="post" action="" class="reviews-form <?php echo esc_attr( $class_reviews_form ); ?>">
    <div class="service">
        <a class="icon" href="<?php echo esc_url( site_url( $wp->request ) ); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 8H1M1 8L8 15M1 8L8 1" stroke="black" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </a>
        <div class="service-logo" style='background-image: url("<?php echo esc_url( $service_logo['url'] ) ?>)'></div>
        <div class="service-info">
            <span class="service-name"><?php echo esc_html__( $service_name ) ?></span>
            <span><a href="<?php echo esc_url( $service_url ) ?>"><?php echo esc_html__( $service_url ) ?></a></span>
        </div>
    </div>
    <hr class="liquidpoll-divider">
    <div class="form-group rating-selected">
        <label class="rating-header"><?php esc_html_e( 'Rate your experience', 'wp-poll' ); ?></label>
		<?php echo liquidpoll_get_review_stars( $rating_selected ); ?>
    </div>

    <div class="form-group review-title">
        <label for="review_title"
               class="review-title-label"><?php esc_html_e( 'Give your review a title', 'wp-poll' ); ?></label>
        <input type="text" id="review_title" name="review_title" placeholder="Impressed with the service!">
    </div>

    <div class="form-group review-textarea">
        <label for="review_description" class="review-label"><?php esc_html_e( 'Briefly tell us about your experience', 'wp-poll' ); ?></label>
        <textarea id="review_description" name="review_description" rows="3" placeholder="Share your experience in detail.."></textarea>
    </div>

    <div class="form-group experience-date">
        <label for="experience_time" class="review-label"><?php esc_html_e( 'When did you have this experience?', 'wp-poll' ) ?></label>
        <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 9H1M14 1V5M6 1V5M5.8 21H14.2C15.8802 21 16.7202 21 17.362 20.673C17.9265 20.3854 18.3854 19.9265 18.673 19.362C19 18.7202 19 17.8802 19 16.2V7.8C19 6.11984 19 5.27976 18.673 4.63803C18.3854 4.07354 17.9265 3.6146 17.362 3.32698C16.7202 3 15.8802 3 14.2 3H5.8C4.11984 3 3.27976 3 2.63803 3.32698C2.07354 3.6146 1.6146 4.07354 1.32698 4.63803C1 5.27976 1 6.11984 1 7.8V16.2C1 17.8802 1 18.7202 1.32698 19.362C1.6146 19.9265 2.07354 20.3854 2.63803 20.673C3.27976 21 4.11984 21 5.8 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input type="text" id="experience_time" name="experience_time" placeholder="DD-MM-YYYY">
    </div>

    <div class="consent">
        <label class="consent-items" for="consent">
            <input type="checkbox" id="consent" name="consent" <?php echo esc_attr( $consent_required ) ?>>
            <span class="liquidpoll-checkbox"></span>
            <span class="consent-desc"><?php echo esc_html__( $consent_desc, 'wp-poll' ) ?></span>
        </label>
    </div>
    <hr class="liquidpoll-divider">
    <div class="submit-section">
        <img class="user-logo" src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
        <p class="user-name"><?php echo esc_html( $current_user->display_name ); ?></p>

        <p class="liquidpoll-responses liquidpoll-success">
            <span class="icon-box"></span><span class="message"></span>
        </p>

		<?php if ( is_user_logged_in() ) : ?>
            <input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->get_id() ); ?>">
            <button type="submit" class="review-submit"><?php esc_html_e( 'Submit your review', 'wp-poll' ); ?></button>
		<?php else: ?>
            <a href="<?php echo esc_url( $login_url ); ?>"
               class="review-submit"><?php esc_html_e( 'Login to continue', 'wp-poll' ); ?></a>
		<?php endif; ?>
    </div>
</form>

<div class="reviews-listing <?php echo esc_attr( $class_reviews_listing ); ?>">

    <form action="" method="get" class="liquidpoll-reviews-rating liquidpoll-review-box">
        <div class="user-meta">
            <div class="avatar">
                <img src="<?php echo esc_url( get_avatar_url( $current_user->user_email ) ); ?>"
                     alt="<?php echo esc_attr( 'poller' ); ?>">
            </div>
            <div class="user">
                <span class="user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
                <span class="user-reviews-count"><?php echo sprintf( esc_html__( '%s Reviews', 'wp-poll' ), liquidpoll_get_poller_submission_count( $current_user->ID, 'reviews' ) ); ?></span>
            </div>
        </div>

        <div class="review-icons">
			<?php echo liquidpoll_get_review_stars(); ?>
            <p class="rating-label">Leave a review</p>
        </div>
    </form>

    <form action="" method="get" class="liquidpoll-reviews-stat liquidpoll-review-box">
        <div class="review-stat-heading">
            <div class="stat-heading">
                <h2>Reviews</h2>
                <span><?php echo sprintf( esc_html__( '%s Reviews', 'wp-poll' ), count( $all_reviews ) ); ?></span>
            </div>
            <div class="stat-rating">
                <div class="star">
                    <svg role="img" aria-label="rating">
                        <use xlink:href="#star"></use>
                    </svg>
                </div>
                <span class="stat"><span class="rating"><?php echo esc_attr( $overall_rating ); ?></span>out of 5</span>
            </div>
        </div>

        <div class="review-stat-filter">

			<?php for ( $index = 5; $index > 0; -- $index ) :

				$rating_times = isset( $all_reviews_rating[ $index ] ) ? $all_reviews_rating[ $index ] : 0;
				$rating_percentage = round( ( $rating_times / count( $all_reviews ) ) * 100 );
				?>

                <label class="stat-filter-item">
                    <input type="radio" name="r"
                           value="<?php echo esc_attr( $index ) ?>" <?php echo esc_attr( checked( $index, $rating_filtered, false ) ) ?>>
                    <span class="liquidpoll-checkbox"></span>
                    <span class="rating-value"><?php echo esc_html__( $index ) ?></span>
                    <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                        <use xlink:href="#star"></use>
                    </svg>
                    <span class="rating-value-bar"><span
                                style="width: <?php echo esc_attr( $rating_percentage ); ?>%"></span></span>
                    <span class="rating-result-value"><?php echo esc_attr( $rating_percentage ); ?>%</span>
                </label>

			<?php endfor; ?>
        </div>
    </form>

    <div class="liquidpoll-reviews-filter">
        <div class="reviews-filter">
            <span class="filter">Filter</span>
            <svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 5L14 5M14 5C14 6.65686 15.3431 8 17 8C18.6569 8 20 6.65685 20 5C20 3.34315 18.6569 2 17 2C15.3431 2 14 3.34315 14 5ZM8 13L20 13M8 13C8 14.6569 6.65685 16 5 16C3.34315 16 2 14.6569 2 13C2 11.3431 3.34315 10 5 10C6.65685 10 8 11.3431 8 13Z"
                      stroke="#5F64EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <form class="reviews-relevant" action="" method="get">
            <span class="filter">Most relevant</span>
            <svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.33333 8.66667H18.6667M2 2H22M8.66667 15.3333H15.3333" stroke="#5F64EB" stroke-width="3"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div class="liquidpoll-relevant-wrap">
                <div class="relevant-items">
                    <label class="relevant-item">
                        <input type="radio" name="relevant" value="DESC">
                        <span class="liquidpoll-checkbox"></span>
                        <span>Newest</span>
                    </label>
                    <label class="relevant-item">
                        <input type="radio" name="relevant" value="ASC">
                        <span class="liquidpoll-checkbox"></span>
                        <span>Oldest</span>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <div class="liquidpoll-reviews-items">

		<?php foreach ( $poll->get_poll_results( $poll_results_query_args ) as $poll_result ) : ?>

			<?php
			$result_id          = Utils::get_args_option( 'id', $poll_result );
			$polled_value       = Utils::get_args_option( 'polled_value', $poll_result, 0 );
			$polled_comments    = Utils::get_args_option( 'polled_comments', $poll_result );
			$poller_id          = Utils::get_args_option( 'poller_id_ip', $poll_result );
			$poller_user        = get_user_by( 'id', $poller_id );
			$datetime           = strtotime( Utils::get_args_option( 'datetime', $poll_result ) );
			$time_ago           = human_time_diff( $datetime, time() );
			$review_title       = liquidpoll_get_results_meta( $result_id, 'review_title' );
			$experience_time    = strtotime( liquidpoll_get_results_meta( $result_id, 'experience_time' ) );
			$experience_time    = date( "F j, Y", $experience_time );
			$current_user_liked = liquidpoll_is_current_user_useful_submitted( $result_id, $current_user->ID ) ? 'active' : '';
			$result_replies     = liquidpoll_get_results_meta( $result_id, 'result_replies', array() );
			$result_replies     = ! is_array( $result_replies ) ? array() : $result_replies;
			?>

            <div class="liquidpoll-reviews-item liquidpoll-review-box">
                <div class="review-box-heading">
                    <div class="user-details">
                        <div class="user-avatar">
                            <img src="<?php echo esc_url( get_avatar_url( $poller_user->user_email ) ); ?>"
                                 alt="<?php echo esc_attr( 'poller' ); ?>">
                        </div>
                        <div class="user-stat">
                            <p class="user-name"><?php echo esc_html( $poller_user->display_name ); ?></p>
                            <p class="user-reviews-count"><?php echo sprintf( esc_html__( '%s Reviews', 'wp-poll' ), liquidpoll_get_poller_submission_count( $poller_id, 'reviews' ) ); ?></p>
                        </div>
                    </div>
                    <div class="review-published">
                        <p class="published-time"><?php echo sprintf( wp_kses_post( '<strong>Posted</strong> %s ago' ), $time_ago ) ?></p>
                        <p class="experienced-time"><?php echo sprintf( wp_kses_post( '<strong>Experienced</strong> %s' ), $experience_time ) ?></p>
                    </div>
                </div>
                <form class="review-stars">
					<?php echo liquidpoll_get_review_stars( $polled_value ); ?>
                </form>
                <div class="review-comment-heading">
                    <h2 class="comment-heading"><?php echo esc_html( $review_title ); ?></h2>
                </div>
                <div class="review-comment">
					<?php echo apply_filters( 'the_content', $polled_comments ); ?>
                </div>
                <hr class="liquidpoll-divider">
                <div class="review-share-wrap">
                    <div class="review-share">
                        <button class="useful <?php echo esc_attr( $current_user_liked ); ?>"
                                data-review-id="<?php echo esc_attr( $result_id ) ?>">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 17V8.2M1 9.8V15.4C1 16.2837 1.71634 17 2.6 17H13.341C14.5256 17 15.533 16.1357 15.7131 14.9649L16.5746 9.36494C16.7983 7.91112 15.6735 6.6 14.2025 6.6H11.4C10.9582 6.6 10.6 6.24183 10.6 5.8V2.97267C10.6 1.8832 9.7168 1 8.62733 1C8.36747 1 8.13198 1.15304 8.02644 1.3905L5.21115 7.72491C5.08275 8.01381 4.79625 8.2 4.4801 8.2H2.6C1.71634 8.2 1 8.91634 1 9.8Z"
                                      stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span><?php esc_html_e( 'Useful', 'wp-poll' ); ?></span>
                        </button>
                        <button class="share">
                            <svg width="17" height="18" viewBox="0 0 17 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.472 10.208L10.936 13.392M10.928 4.608L5.472 7.792M15.4 3.4C15.4 4.72548 14.3255 5.8 13 5.8C11.6745 5.8 10.6 4.72548 10.6 3.4C10.6 2.07452 11.6745 1 13 1C14.3255 1 15.4 2.07452 15.4 3.4ZM5.8 9C5.8 10.3255 4.72548 11.4 3.4 11.4C2.07452 11.4 1 10.3255 1 9C1 7.67452 2.07452 6.6 3.4 6.6C4.72548 6.6 5.8 7.67452 5.8 9ZM15.4 14.6C15.4 15.9255 14.3255 17 13 17C11.6745 17 10.6 15.9255 10.6 14.6C10.6 13.2745 11.6745 12.2 13 12.2C14.3255 12.2 15.4 13.2745 15.4 14.6Z"
                                      stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span><?php esc_html_e( 'Share', 'wp-poll' ); ?></span>
                        </button>
                    </div>
                    <div class="review-report">
                        <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 11.4C1 11.4 1.8 10.6 4.2 10.6C6.6 10.6 8.2 12.2 10.6 12.2C13 12.2 13.8 11.4 13.8 11.4V1.8C13.8 1.8 13 2.6 10.6 2.6C8.2 2.6 6.6 1 4.2 1C1.8 1 1 1.8 1 1.8L1 17"
                                  stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

				<?php if ( count( $result_replies ) > 0 ) : ?>
                    <div class="review-replay">
						<?php foreach ( $result_replies as $reply ): $reply_author = get_user_by( 'id', Utils::get_args_option( 'user_id', $reply ) ); ?>
                            <div class="replay-info-wrap">
                                <div class="replay-info">
                                    <div class="replay-icon">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 2V3.4C2 6.76031 2 8.44047 2.65396 9.72394C3.2292 10.8529 4.14708 11.7708 5.27606 12.346C6.55953 13 8.23969 13 11.6 13H18M18 13L13 8M18 13L13 18"
                                                  stroke="#5F64EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div class="service-logo"><img src="<?php echo esc_url( get_avatar_url( $reply_author->ID ) ); ?>" alt="<?php echo esc_attr( $reply_author->display_name ); ?>"></div>
                                    <div class="service-info">
                                        <span class="service-name"><?php echo esc_html( $reply_author->display_name ); ?></span>
                                        <span><?php echo sprintf( esc_html__( 'Replied %s ago', 'wp-poll' ), human_time_diff( strtotime( Utils::get_args_option( 'datetime', $reply ) ), time() ) ) ?></span>
                                    </div>
                                </div>
                                <p class="reply-date"><?php echo date( "jS M Y", strtotime( Utils::get_args_option( 'datetime', $reply ) ) ); ?></p>
                            </div>
                            <div class="replay"><?php echo wpautop( Utils::get_args_option( 'reply_content', $reply ) ); ?></div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>

            </div>
		<?php endforeach; ?>

    </div>

    <div class="liquidpoll-filter-modal-wrap">

        <form class="liquidpoll-filter-modal">
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

					<?php for ( $index = 1; $index <= 5; $index ++ ) : ?>
                        <div>
                            <input type="checkbox" id="rating-<?php echo esc_attr( $index ) ?>" name="filter_rating[]"
                                   value="<?php echo esc_attr( $index ) ?>">
                            <label class="filter-star" for="rating-<?php echo esc_attr( $index ) ?>">
                                <span><?php echo esc_html__( $index ) ?></span>
                                <svg class="rating-star star-icon fill" role="img" aria-label="rating">
                                    <use xlink:href="#star"></use>
                                </svg>
                            </label>
                        </div>
					<?php endfor; ?>

                </div>
            </div>

            <div class="filter-date">
                <span>Date posted</span>
                <div class="filter-date-items">
                    <label class="date-item">
                        <input type="radio" name="filter_date" value="all">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">All reviews</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter_date" value="last_30">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 30 days</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter_date" value="last_3">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 3 months</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter_date" value="last_6">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 6 months</span>
                    </label>
                    <label class="date-item">
                        <input type="radio" name="filter_date" value="last_12">
                        <span class="liquidpoll-checkbox"></span>
                        <span class="date-label">Last 12 months</span>
                    </label>
                </div>
            </div>

            <hr class="liquidpoll-divider">

            <div class="filter-footer">
                <button type="reset" class="button-reset">Reset</button>
                <button type="submit" class="button-filter">Filter</button>
            </div>

        </form>

    </div>

</div>