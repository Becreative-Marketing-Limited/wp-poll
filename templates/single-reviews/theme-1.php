<?php
/**
 * Single Review - Title
 */

defined( 'ABSPATH' ) || exit;

global $poll, $liquidpoll;

?>

<div class="liquidpoll-reviews-rating">
    <div class="user-meta">
        <div class="avatar">
            <img src="<?php echo esc_url('http://2.gravatar.com/avatar/2d75625795a61ae2686783f289f938a8?s=96&d=mm&r=g'); ?>" alt="<?php echo esc_attr( 'poller' ); ?>">
        </div>
        <div class="user">
            <span class="user-name">Maya Nunez</span>
            <span class="user-reviews-count">47 Reviews</span>
        </div>
    </div>
    <div class="rating-wrap">
        <div class="rating">
            <label class="star empty-star" data-value="1">
                <input type="radio" name="reaction" value="1">
				<?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="2">
				<?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="3">
				<?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="4">
				<?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="5">
				<?php echo render_review_star() ?>
            </label>
        </div>
        <p class="rating-label">Leave a review</p>
    </div>
</div>


<form class="review-form">
    <div class="service">
        <div class="icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 8H1M1 8L8 15M1 8L8 1" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="service-logo">
            <img src="">
        </div>
        <div class="service-info">
            <span class="service-name">Liquid Poll Pro</span>
            <span><a href="#">liquidpoll.com</a></span>
        </div>
    </div>
    <div class="divider"></div>
    <div class="rating-wrap">
        <div class="rating-label">
            <label class="rating-header">Rate your experience</label>
        </div>
        <div class="rating">
            <label class="star empty-star" data-value="1">
                <input type="radio" name="reaction" value="1">
			    <?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="2">
			    <?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="3">
			    <?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="4">
			    <?php echo render_review_star() ?>
            </label>
            <label class="star empty-star">
                <input type="radio" name="reaction" value="5">
			    <?php echo render_review_star() ?>
            </label>
        </div>
    </div>
    <div class="review-title">
        <div class="title-label">
            <label for="title" class="review-title-label">Give your review a title</label>
            <input type="text" id="title" name="title" placeholder="Impressed with the service!">
        </div>
    </div>
    <div class="review-textarea">
        <label for="review" class="review-label">Briefly tell us about your experience</label>
        <textarea id="review" name="review"></textarea>
    </div>

    <div class="experience-date">
        <label for="experience-time" class="review-label">When did you have this experience?</label>
        <input type="datetime-local" id="experience-time" name="experience-time">
    </div>

    <div class="consent">
        <label for="rdo1">
            <input type="checkbox" id="rdo1" name="radio">
            <span class="rdo"></span>
            <span>I confirm this review about my own genuine experience. I am eligible to leave this review, and have not been offered any incentive or payment to leave a review for this company</span>
        </label>
    </div>
    <div class="divider"></div>
    <div class="submit-section">
        <img class="user-logo" src="">
        <p class="user-name">Miranda Ash-Patel</p>
        <button type="submit" class="review-submit">Submit your review</button>
    </div>
</form>
