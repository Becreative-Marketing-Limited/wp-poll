<?php
/**
 * Slider Widget - 1
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

$styles = isset( $args['styles'] ) ? $args['styles'] : '1';

if ( ! isset( $args['id'] ) ) {
	return;
}

$poll_id              = $args['id'];
$poll                 = liquidpoll_get_poll( $poll_id );
$single_review_url    = $poll->get_permalink();
$slider_button_styles = $args['slider_button'] ?? 1;
$all_reviews          = $poll->get_poll_results();
$all_reviews_rating   = array();
$all_reviews_value    = 0;

foreach ( $all_reviews as $review ) {

	$polled_value = Utils::get_args_option( 'polled_value', $review );

	if ( ! empty( $polled_value ) && $polled_value >= 0 && $polled_value <= 5 ) {
		$polled_value = ( ( $polled_value / 0.5 ) % 2 !== 0 ) ? $polled_value + 0.5 : $polled_value;

		$all_reviews_value                   += $polled_value;
		$all_reviews_rating[ $polled_value ] = ( $all_reviews_rating[ $polled_value ] ?? 0 ) + 1;
	}
}

$overall_rating = count( $all_reviews ) > 0 ? round( $all_reviews_value / count( $all_reviews ), 1 ) : 0;

?>

<div class="reviews-slider style-<?php echo esc_attr( $styles ); ?>">
	<div class="slider-heading-wrap">
		<div class="slider-heading">
			<span class="rate">Rated Excellent!</span>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( $overall_rating ); ?>
			</form>
			<div class="review-count">
				<span>Based on</span>
				<span class="count"><?php echo sprintf( esc_html__( '%s Reviews', 'wp-poll' ), count( $all_reviews ) ); ?></span>
			</div>
		</div>
	</div>
	<div class="reviews-wrap style-3">

		<?php foreach ( $poll->get_poll_results( array() ) as $poll_result ) : ?>

			<?php
			$result_id       = Utils::get_args_option( 'id', $poll_result );
			$polled_value    = Utils::get_args_option( 'polled_value', $poll_result, 0 );
			$polled_comments = Utils::get_args_option( 'polled_comments', $poll_result );
			$poller_id       = Utils::get_args_option( 'poller_id_ip', $poll_result );
			$poller_user     = get_user_by( 'id', $poller_id );
			$review_title    = liquidpoll_get_results_meta( $result_id, 'review_title' );
			?>

			<div class="single-review">
				<div class="user">
					<div class="user-avatar">
						<img src="<?php echo esc_url( get_avatar_url( $poller_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
					</div>
					<div class="user-info">
						<span class="user-name"><?php echo esc_html( $poller_user->display_name ); ?></span>
						<div class="user-location">
							<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span class="location">United Kingdom</span>
						</div>
					</div>
				</div>
				<form class="review-stars">
					<?php echo liquidpoll_get_review_stars( $polled_value ); ?>
				</form>
				<div class="review-comment-heading">
					<span class="comment-heading"><?php echo esc_html( $review_title ); ?></span>
				</div>
				<div class="review-comment">
					<?php echo apply_filters( 'the_content', wp_trim_words( $polled_comments, 12 ) ); ?>
				</div>
				<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
			</div>

		<?php endforeach; ?>

		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
		<div class="single-review">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment-heading">
				<span class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></span>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="<?php echo esc_url( $single_review_url ) ?>" target="_blank">Read full review</a>
		</div>
	</div>
</div>

<script>
    jQuery('.reviews-wrap.style-3').slick({
        infinite: true,
        speed: 500,
	    mobileFirst: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: false,
        nextArrow: '<button type="button" class="style-<?php echo esc_attr( $slider_button_styles ); ?> slick-next">Next</button>',
        responsive: [
            {
                breakpoint: 1280,
                settings: {
                    slidesToShow: 5,
                }
            },
            {
                breakpoint: 1180,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 910,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            }

        ]
    });
</script>
