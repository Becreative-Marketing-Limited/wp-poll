<?php
/**
 * Tiles Widget - 1
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

if ( ! isset( $args['id'] ) ) {
	return;
}

$poll_id              = $args['id'];
$poll                 = liquidpoll_get_poll( $poll_id );
$single_review_url    = $poll->get_permalink();
$styles               = $args['styles'] ?? '1';
$slider_button_styles = $args['slider_button'] ?? 1;
$global_rating        = $args['show_global_rating'] ?? 'yes';
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

<div class="reviews-tiles style-<?php echo esc_attr( $styles ); ?>">

	<?php if ( 'yes' == $global_rating ) : ?>

        <div class="slider-heading-wrap">
            <div class="slider-heading">
                <div class="rate-wrap">
                    <span class="rate">Rated Excellent!</span>
                    <div class="review-count">
                        <span>Based on</span>
                        <span class="count"><?php echo sprintf( esc_html__( '%s Reviews', 'wp-poll' ), count( $all_reviews ) ); ?></span>
                    </div>
                </div>
                <form class="review-stars">
					<?php echo liquidpoll_get_review_stars( $overall_rating ); ?>
                </form>
            </div>
        </div>

	<?php endif; ?>

    <div class="reviews-wrap tiles-grid">

		<?php foreach ( $poll->get_poll_results( array() ) as $poll_result ) : ?>

			<?php
			$result_id         = Utils::get_args_option( 'id', $poll_result );
			$polled_value      = Utils::get_args_option( 'polled_value', $poll_result, 0 );
			$polled_comments   = Utils::get_args_option( 'polled_comments', $poll_result );
			$poller_id         = Utils::get_args_option( 'poller_id_ip', $poll_result );
			$poller_user       = get_user_by( 'id', $poller_id );
			$review_title      = liquidpoll_get_results_meta( $result_id, 'review_title' );
			$poller_ip_address = liquidpoll_get_results_meta( $result_id, 'poller_ip_address' );
			$datetime          = strtotime( Utils::get_args_option( 'datetime', $poll_result ) );
			$time_ago          = human_time_diff( $datetime, time() );
			?>

            <div class="single-review">
                <div class="user-avatar">
                    <img src="<?php echo esc_url( get_avatar_url( $poller_user->user_email ) ); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
                </div>
                <div class="icon-quote">
                    <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z" fill="#C7C9FF"/>
                    </svg>
                </div>
                <span class="user-name"><?php echo esc_html( $poller_user->display_name ); ?></span>
                <div class="user-location">
                    <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="location"><?php echo esc_html__( liquidpoll_get_poller_location( $poller_ip_address ) ) ?></span>
                </div>
                <form class="review-stars">
					<?php echo liquidpoll_get_review_stars( $polled_value ); ?>
                </form>
                <div class="review-comment-heading">
                    <span class="comment-heading"><?php echo esc_html( $review_title ); ?></span>
                </div>
                <p class="publish-time"><?php echo sprintf( esc_html__( '%s ago', 'wp-poll' ), $time_ago ); ?></p>
                <div class="review-comment">
					<?php echo wpautop( $polled_comments ); ?>
                </div>
            </div>

		<?php endforeach; ?>

        <div class="single-review">
            <div class="user-avatar">
                <img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
            </div>
            <div class="icon-quote">
                <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z"
                          fill="#C7C9FF"/>
                </svg>
            </div>
            <span class="user-name">Miranda Ash-Patel</span>
            <div class="user-location">
                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="location">United Kingdom</span>
            </div>
            <form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
            </form>
            <div class="review-comment-heading">
                <h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
            </div>
            <p class="publish-time">2 days ago</p>
            <div class="review-comment">
				<?php echo wpautop(  'Couldn’t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended.' ); ?>
            </div>
        </div>
        <div class="single-review">
            <div class="user-avatar">
                <img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
            </div>
            <div class="icon-quote">
                <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z"
                          fill="#C7C9FF"/>
                </svg>
            </div>
            <span class="user-name">Miranda Ash-Patel</span>
            <div class="user-location">
                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="location">United Kingdom</span>
            </div>
            <form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
            </form>
            <div class="review-comment-heading">
                <h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
            </div>
            <p class="publish-time">2 days ago</p>
            <div class="review-comment">
				<?php echo wpautop( 'Couldn’t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended.t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommendedt have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended' ); ?>
            </div>
        </div>
        <div class="single-review">
            <div class="user-avatar">
                <img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
            </div>
            <div class="icon-quote">
                <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z"
                          fill="#C7C9FF"/>
                </svg>
            </div>
            <span class="user-name">Miranda Ash-Patel</span>
            <div class="user-location">
                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="location">United Kingdom</span>
            </div>
            <form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
            </form>
            <div class="review-comment-heading">
                <h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
            </div>
            <p class="publish-time">2 days ago</p>
            <div class="review-comment">
				<?php echo apply_filters( 'the_content', 'better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommendedt have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended' ); ?>
            </div>
        </div>
        <div class="single-review">
            <div class="user-avatar">
                <img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
            </div>
            <div class="icon-quote">
                <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z" fill="#C7C9FF"/>
                </svg>
            </div>
            <span class="user-name">Miranda Ash-Patel</span>
            <div class="user-location">
                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="location">United Kingdom</span>
            </div>
            <form class="review-stars">
		        <?php echo liquidpoll_get_review_stars( 4 ); ?>
            </form>
            <div class="review-comment-heading">
                <h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
            </div>
            <p class="publish-time">2 days ago</p>
            <div class="review-comment">
		        <?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended.' ); ?>
            </div>
        </div>
        <div class="single-review">
            <div class="user-avatar">
                <img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
            </div>
            <div class="icon-quote">
                <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z"
                          fill="#C7C9FF"/>
                </svg>
            </div>
            <span class="user-name">Miranda Ash-Patel</span>
            <div class="user-location">
                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="location">United Kingdom</span>
            </div>
            <form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
            </form>
            <div class="review-comment-heading">
                <h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
            </div>
            <p class="publish-time">2 days ago</p>
            <div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended.t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommendedt have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended' ); ?>
            </div>
        </div>
        <div class="single-review">
            <div class="user-avatar">
                <img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
            </div>
            <div class="icon-quote">
                <svg width="45" height="39" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 32.299C3.39519 31.4055 5.94158 29.8866 7.63917 27.7423C9.33677 25.5979 10.2302 22.3814 10.3196 18.0928H0V0H18.4948V13.6701C18.4948 17.4227 18.1821 20.7285 17.5567 23.5876C16.9313 26.4467 15.9038 28.9038 14.4742 30.9588C13.0447 32.9244 11.1237 34.5773 8.71134 35.9175C6.38831 37.2577 3.48454 38.2852 0 39V32.299ZM26.134 32.299C29.5292 31.4055 32.0756 29.8866 33.7732 27.7423C35.4708 25.5979 36.3643 22.3814 36.4536 18.0928H26.134V0H44.6289V13.6701C44.6289 17.4227 44.3161 20.7285 43.6907 23.5876C43.0653 26.4467 42.0378 28.9038 40.6082 30.9588C39.1787 32.9244 37.2577 34.5773 34.8454 35.9175C32.5223 37.2577 29.6186 38.2852 26.134 39V32.299Z"
                          fill="#C7C9FF"/>
                </svg>
            </div>
            <span class="user-name">Miranda Ash-Patel</span>
            <div class="user-location">
                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z"
                          stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="location">United Kingdom</span>
            </div>
            <form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
            </form>
            <div class="review-comment-heading">
                <h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
            </div>
            <p class="publish-time">2 days ago</p>
            <div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended.t have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommendedt have had a better experience than I have with SUNS lifestyle from start to finish fast and efficient and kept informed every step of the way.The fitters Christian and Brent with absolutely brilliant, friendly and helpful and have done a fantastic job. Highly recommended' ); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(window).on('load', function () {
        jQuery('.tiles-grid').masonry({
            // set itemSelector so .grid-sizer is not used in layout
            itemSelector: '.single-review',
            columnWidth: '.single-review',
            fitWidth: true,
            gutter: 20,
            horizontalOrder: true,
        });
    });
</script>
