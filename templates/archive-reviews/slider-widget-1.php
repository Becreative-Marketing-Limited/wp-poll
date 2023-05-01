<?php
/**
 * Slider Widget - 1
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="reviews-slider">
	<div class="slider-heading">
		<h2>Rated</h2>
		<form class="review-stars">
			<?php echo liquidpoll_get_review_stars( 4 ); ?>
		</form>
		<div class="review-count">
			<span>Based on</span>
			<span class="count">225 Reviews</span>
		</div>
	</div>
</div>


