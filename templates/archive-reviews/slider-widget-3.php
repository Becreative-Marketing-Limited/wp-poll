<?php
/**
 * Slider Widget - 1
 */

defined( 'ABSPATH' ) || exit;

$styles = isset($args['styles']) ? $args['styles'] : '1';

?>

<div class="reviews-slider">
	<div class="slider-heading-wrap">
		<div class="slider-heading">
			<span class="rate">Rated</span>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-count">
				<span>Based on</span>
				<span class="count">225 Reviews</span>
			</div>
		</div>
		<p class="rating">4 out of 5</p>
	</div>
	<div class="reviews-wrap style-<?php echo esc_attr($styles); ?>">
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
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="">Read full review</a>
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
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="">Read full review</a>
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
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="">Read full review</a>
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
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="">Read full review</a>
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
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<a class="review-link" href="">Read full review</a>
		</div>
	</div>
</div>


