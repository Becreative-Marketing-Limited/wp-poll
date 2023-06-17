<?php
/**
 *Review Single
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;


global $wp, $wp_query, $wpdb;

$result_id = $wp_query->get( 'reviews' );

$poll_data   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . LIQUIDPOLL_RESULTS_TABLE . " WHERE id = %d", $result_id ), ARRAY_A );
$poll_result = reset( $poll_data );

$polled_value        = Utils::get_args_option( 'polled_value', $poll_result, 0 );
$polled_comments     = Utils::get_args_option( 'polled_comments', $poll_result );
$poller_id           = Utils::get_args_option( 'poller_id_ip', $poll_result );
$poller_user         = get_user_by( 'id', $poller_id );
$datetime            = strtotime( Utils::get_args_option( 'datetime', $poll_result ) );
$time_ago            = human_time_diff( $datetime, time() );
$review_title        = liquidpoll_get_results_meta( $result_id, 'review_title' );
$experience_time     = strtotime( liquidpoll_get_results_meta( $result_id, 'experience_time' ) );
$experience_time     = date( "F j, Y", $experience_time );
$current_user_liked  = liquidpoll_is_current_user_useful_submitted( $result_id, $current_user->ID ) ? 'active' : '';
$result_replies      = liquidpoll_get_results_meta( $result_id, 'result_replies', array() );
$result_replies      = ! is_array( $result_replies ) ? array() : $result_replies;
$feature_image       = Utils::get_option( '_reviews_feature_image' );
$image               = $feature_image['url'] ?? '';
$single_review_url   = site_url( "reviews/{$result_id}" );
$fb_sharer_url       = "http://www.facebook.com/sharer.php?u={$single_review_url}";
$linkedin_sharer_url = "https://www.linkedin.com/shareArticle?mini=true&url={$single_review_url}";
$twitter_sharer_url  = "http://twitter.com/share?text={$review_title}&url={$single_review_url}";

global $result_id, $polled_comments, $review_title, $image;


if ( ! function_exists( 'add_opengraph_meta' ) ) {
	function add_opengraph_meta() {
		global $result_id, $polled_comments, $review_title, $image;

		?>

        <meta property="og:title" content="<?php echo $review_title ?>"/>
        <meta property="og:description" content="<?php echo $polled_comments; ?>"/>
        <meta property="og:type" content="article"/>
        <meta property="og:url" content="<?php echo site_url( "reviews/{$result_id}" ) ?>"/>
        <meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
        <meta property="og:image" content="<?php echo $image; ?>"/>

		<?php
	}
}
add_action( 'wp_head', 'add_opengraph_meta' );

get_header();

?>

    <div class="reviews-single">
        <div class="reviews-main">
            <div class="liquidpoll-reviews-items">

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
                        <a target="_blank" href="<?php echo esc_url( $single_review_url ) ?>">
                            <h2 class="comment-heading"><?php echo esc_html( $review_title ); ?></h2>
                        </a>
                    </div>
                    <div class="review-comment">
						<?php echo apply_filters( 'the_content', $polled_comments ); ?>
                    </div>
                    <hr class="liquidpoll-divider">
                    <div class="review-share-wrap">
                        <div class="review-share">
                            <button class="useful <?php echo esc_attr( $current_user_liked ); ?>" data-review-id="<?php echo esc_attr( $result_id ) ?>">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 17V8.2M1 9.8V15.4C1 16.2837 1.71634 17 2.6 17H13.341C14.5256 17 15.533 16.1357 15.7131 14.9649L16.5746 9.36494C16.7983 7.91112 15.6735 6.6 14.2025 6.6H11.4C10.9582 6.6 10.6 6.24183 10.6 5.8V2.97267C10.6 1.8832 9.7168 1 8.62733 1C8.36747 1 8.13198 1.15304 8.02644 1.3905L5.21115 7.72491C5.08275 8.01381 4.79625 8.2 4.4801 8.2H2.6C1.71634 8.2 1 8.91634 1 9.8Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e( 'Useful', 'wp-poll' ); ?></span>
                            </button>
                            <button class="share">
                                <svg width="17" height="18" viewBox="0 0 17 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.472 10.208L10.936 13.392M10.928 4.608L5.472 7.792M15.4 3.4C15.4 4.72548 14.3255 5.8 13 5.8C11.6745 5.8 10.6 4.72548 10.6 3.4C10.6 2.07452 11.6745 1 13 1C14.3255 1 15.4 2.07452 15.4 3.4ZM5.8 9C5.8 10.3255 4.72548 11.4 3.4 11.4C2.07452 11.4 1 10.3255 1 9C1 7.67452 2.07452 6.6 3.4 6.6C4.72548 6.6 5.8 7.67452 5.8 9ZM15.4 14.6C15.4 15.9255 14.3255 17 13 17C11.6745 17 10.6 15.9255 10.6 14.6C10.6 13.2745 11.6745 12.2 13 12.2C14.3255 12.2 15.4 13.2745 15.4 14.6Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e( 'Share', 'wp-poll' ); ?></span>
                                <div class="social-share-wrap">
                                    <div class="social-share">
                                        <span class="social-share-title">Share this review</span>
                                        <div class="social">
                                            <a class="social-link" href=<?php echo esc_url( $fb_sharer_url ) ?>"">
                                                <svg width="35" height="36" viewBox="0 0 35 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_2224_4660)">
                                                        <rect y="0.5" width="35" height="35" rx="17.5" fill="white"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M35 0.5H0V35.5H35V0.5ZM21.9303 15.9275L21.4316 19.0931H18.8584V26.75H15.4026V19.0931H12.6V15.9275H15.4026V13.5137C15.4026 10.7685 17.0532 9.25 19.5815 9.25C20.4168 9.2602 21.2502 9.33129 22.0749 9.46269V12.1584H20.6587C19.2873 12.1584 18.8584 13.0042 18.8584 13.8698V15.9275H21.9303Z" fill="#337FFF"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_2224_4660">
                                                            <rect y="0.5" width="35" height="35" rx="17.5" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                <span class="social-name">Share on Facebook</span>
                                            </a>
                                        </div>
                                        <div class="social">
                                            <a class="social-link" href="<?php echo esc_url( $twitter_sharer_url ) ?>">
                                                <svg width="35" height="36" viewBox="0 0 35 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_2224_4646)">
                                                        <rect y="0.5" width="35" height="35" rx="17.5" fill="white"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M35 0.5H0V35.5H35V0.5ZM24.08 13.4416C24.6497 13.3561 25.206 13.1976 25.7351 12.9698C25.777 12.9522 25.7946 12.941 25.802 12.9457C25.8074 12.9491 25.8074 12.961 25.8074 12.9851C25.8122 13.0846 25.7796 13.1823 25.7161 13.259C25.3493 13.7738 24.9097 14.2326 24.411 14.6211C24.3979 14.6308 24.383 14.6407 24.3674 14.6511C24.2972 14.6979 24.2132 14.754 24.2132 14.838C24.2132 17.5394 23.5207 20.2371 21.5498 22.1927C19.0424 24.6849 15.2833 25.3622 11.9426 24.4414C11.2976 24.2569 10.6749 24.0017 10.0859 23.6804C9.888 23.5739 9.69776 23.4636 9.50752 23.338C9.46567 23.2999 9.42762 23.2581 9.46567 23.2391C9.50371 23.22 9.53796 23.2162 9.68635 23.2391C10.6272 23.3228 11.5749 23.2007 12.4639 22.8814C12.5037 22.8677 12.5473 22.853 12.594 22.8373C13.119 22.6601 14.0339 22.3514 14.275 21.9112L14.2978 21.8884C14.2151 21.87 14.1379 21.8627 14.063 21.8556C14.0138 21.851 13.9656 21.8464 13.9173 21.8389C13.027 21.5802 11.8132 21.116 11.2539 19.594C11.2197 19.5065 11.2539 19.4571 11.3453 19.4761C11.7693 19.5336 12.2 19.5207 12.6199 19.438C12.4723 19.4087 12.3282 19.3641 12.1899 19.3049C11.6226 19.0755 11.1264 18.6996 10.7521 18.2156C10.3777 17.7316 10.1386 17.1569 10.0592 16.5502C10.0484 16.4236 10.0484 16.2963 10.0592 16.1697C10.063 16.0784 10.1049 16.0517 10.181 16.1012C10.5862 16.3076 11.0287 16.4305 11.4822 16.4627C11.3376 16.3485 11.2007 16.2268 11.0675 16.1012C10.0782 15.1614 9.69396 13.259 10.4854 12.0567C10.55 11.9654 10.5919 11.9654 10.6718 12.0567C12.4905 14.1227 14.7163 15.0853 17.4292 15.4848C17.4977 15.4962 17.4977 15.4848 17.4977 15.4049C17.4178 14.9379 17.4294 14.4597 17.5319 13.9971C17.6562 13.5108 17.8833 13.0567 18.1978 12.6654C18.4967 12.2853 18.8694 11.9695 19.2935 11.7371C19.7242 11.5166 20.1956 11.3871 20.6785 11.3566C21.1761 11.3251 21.6744 11.407 22.1358 11.5963C22.4775 11.7477 22.7934 11.9518 23.0717 12.2013C23.1326 12.2571 23.1898 12.3168 23.243 12.3801C23.2605 12.3977 23.2826 12.4102 23.3067 12.4162C23.3308 12.4222 23.3561 12.4216 23.3799 12.4143C24.0625 12.244 24.7208 11.9883 25.3394 11.6534C25.3528 11.646 25.3679 11.6422 25.3832 11.6422C25.3985 11.6422 25.4135 11.646 25.4269 11.6534C25.4448 11.6661 25.4387 11.6875 25.4327 11.7082C25.4298 11.7184 25.4269 11.7283 25.4269 11.7371C25.3119 12.0957 25.1313 12.4298 24.8943 12.7225C24.7383 12.9166 24.3464 13.396 24.08 13.4416Z"
                                                              fill="#33CCFF"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_2224_4646">
                                                            <rect y="0.5" width="35" height="35" rx="17.5" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                <span class="social-name">Share on Twitter</span>
                                            </a>
                                        </div>
                                        <div class="social">
                                            <a class="social-link" href="<?php echo esc_url( $linkedin_sharer_url ) ?>">
                                                <svg width="35" height="36" viewBox="0 0 35 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_2224_4642)">
                                                        <rect y="0.5" width="35" height="35" rx="17.5" fill="white"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M35 0.5H0V35.5H35V0.5ZM8.05 9.91992C8.05 9.16377 8.68304 8.55 9.4634 8.55H25.7608C26.5415 8.55 27.1742 9.16377 27.1742 9.91992V26.3045C27.1742 27.0609 26.5415 27.6742 25.7608 27.6742H9.4634C8.68312 27.6742 8.05 27.061 8.05 26.3048V9.91992ZM13.8615 24.5545V15.9447H10.9998V24.5545H13.8615ZM12.4312 14.7694C13.429 14.7694 14.0501 14.1083 14.0501 13.282C14.0315 12.437 13.429 11.7943 12.4502 11.7943C11.4708 11.7943 10.8311 12.437 10.8311 13.282C10.8311 14.1082 11.452 14.7693 12.4125 14.7693L12.4312 14.7694ZM15.4455 24.5545H18.3071V19.7469C18.3071 19.49 18.3258 19.2323 18.4014 19.0488C18.6081 18.5344 19.079 18.002 19.8697 18.002C20.905 18.002 21.3194 18.7915 21.3194 19.949V24.5545H24.1808V19.6179C24.1808 16.9735 22.7692 15.7429 20.8865 15.7429C19.3685 15.7429 18.6877 16.5772 18.3073 17.1637V15.945H15.4457C15.483 16.7527 15.4455 24.5545 15.4455 24.5545Z" fill="#006699"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_2224_4642">
                                                            <rect y="0.5" width="35" height="35" rx="17.5" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                <span class="social-name">Share on Linkedin</span>
                                            </a>
                                        </div>
                                        <div class="social">
                                            <p class="social-link copy-url tt--hint tt--top" aria-label="Click to Copy" data-text-copied="URL Copied">
                                                <input id="singleReview" type="hidden" value="<?php echo esc_url( $single_review_url ) ?>">
                                                <svg width="35" height="36" viewBox="0 0 35 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="17.5" cy="18" r="17.5" fill="#D9D9D9"/>
                                                    <path d="M16.3325 19.3333C16.6904 19.8118 17.147 20.2077 17.6713 20.4941C18.1956 20.7806 18.7754 20.9509 19.3714 20.9936C19.9674 21.0363 20.5655 20.9503 21.1253 20.7415C21.6851 20.5327 22.1935 20.2059 22.6159 19.7833L25.1159 17.2833C25.8748 16.4975 26.2948 15.445 26.2853 14.3525C26.2758 13.26 25.8376 12.215 25.0651 11.4424C24.2926 10.6699 23.2475 10.2317 22.155 10.2222C21.0625 10.2127 20.01 10.6327 19.2242 11.3917L17.7909 12.8167M19.6659 17.6667C19.308 17.1882 18.8514 16.7924 18.3271 16.5059C17.8027 16.2194 17.2229 16.0491 16.627 16.0064C16.031 15.9637 15.4329 16.0497 14.8731 16.2585C14.3133 16.4673 13.8049 16.7941 13.3825 17.2167L10.8825 19.7167C10.1235 20.5025 9.70355 21.555 9.71305 22.6475C9.72254 23.74 10.1607 24.7851 10.9333 25.5576C11.7058 26.3301 12.7509 26.7683 13.8434 26.7778C14.9358 26.7873 15.9883 26.3673 16.7742 25.6083L18.1992 24.1833" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <span class="social-name">Copy URL link</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="review-report" data-review-id="<?php echo esc_attr( $result_id ) ?>">
                            <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 11.4C1 11.4 1.8 10.6 4.2 10.6C6.6 10.6 8.2 12.2 10.6 12.2C13 12.2 13.8 11.4 13.8 11.4V1.8C13.8 1.8 13 2.6 10.6 2.6C8.2 2.6 6.6 1 4.2 1C1.8 1 1 1.8 1 1.8L1 17" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
                                                <path d="M2 2V3.4C2 6.76031 2 8.44047 2.65396 9.72394C3.2292 10.8529 4.14708 11.7708 5.27606 12.346C6.55953 13 8.23969 13 11.6 13H18M18 13L13 8M18 13L13 18" stroke="#5F64EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="service-info">
                                            <div class="service-logo"><img src="<?php echo esc_url( get_avatar_url( $reply_author->ID ) ); ?>" alt="<?php echo esc_attr( $reply_author->display_name ); ?>"></div>
                                            <div class="service-details">
                                                <span class="service-name"><?php echo esc_html( $reply_author->display_name ); ?></span>
                                                <span><?php echo sprintf( esc_html__( 'Replied %s ago', 'wp-poll' ), human_time_diff( strtotime( Utils::get_args_option( 'datetime', $reply ) ), time() ) ) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="reply-date"><?php echo date( "jS M Y", strtotime( Utils::get_args_option( 'datetime', $reply ) ) ); ?></p>
                                </div>
                                <div class="replay"><?php echo wpautop( Utils::get_args_option( 'reply_content', $reply ) ); ?></div>
							<?php endforeach; ?>
                        </div>
					<?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php

get_footer();