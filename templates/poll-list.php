<?php
/*
* @Author 		ParaTheme
* @Folder	 	wp-resume-builder/templates

* Copyright: 	2015 ParaTheme
*/

if ( ! defined('ABSPATH')) exit; // if direct access 
	
	$liquidpoll_list_per_page = get_option( 'liquidpoll_list_per_page' );
	if( empty( $liquidpoll_list_per_page ) ) $liquidpoll_list_per_page = 10;
	
	if ( get_query_var('paged') ) { $paged = get_query_var('paged');} 
	elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } 
	else { $paged = 1; }
	
	$LIQUIDPOLL_Poll_query = new WP_Query( array (
		'post_type' => 'poll',
		'post_status' => array( 'publish' ),
		'order' => 'DESC',
		'orderby' => 'date',
		'posts_per_page' => $liquidpoll_list_per_page,
		'paged' => $paged,
	) );
			

?>

<div class="liquidpoll_list_container">
	
	<?php 
	if ( $LIQUIDPOLL_Poll_query->have_posts() ) : 
	while ( $LIQUIDPOLL_Poll_query->have_posts() ) : $LIQUIDPOLL_Poll_query->the_post();
		
		$poll_title 	= get_the_title();
		$poll_permalink = get_the_permalink();
		$polled_data	= get_post_meta( get_the_ID(), 'polled_data', true );
		$poller 		= empty($polled_data) ? 0 : count( $polled_data );
		$time_ago 		= human_time_diff( get_the_time('U'), current_time('timestamp') ) .__(' ago','wp-poll');
		$terms 			= get_the_terms( get_the_ID(), 'poll_cat' );
		$terms_html 	= '';
		$count 			= 0;
		
		foreach( $terms as $term ){
			$terms_html .= $term->name;
			
			if( ++$count < count($terms) ) $terms_html .= ', ';
		}

		echo "
		<div class='liquidpoll_list_single'>
			<div class='liquidpoll_left'>
				<div class='liquidpoll_icon dashicons-before dashicons-chart-bar'></div>
			</div>
			<div class='liquidpoll_right'>
				<a class='liquidpoll_title' href='$poll_permalink'>$poll_title</a>
				<a class='inline liquidpoll_categories'><i class='fa fa-folder-open'></i> $terms_html</a>
				<a class='inline liquidpoll_report'><i class='fa fa-paper-plane'></i> $poller ".esc_html__('Response(s)','wp-poll')."</a>
				<a class='inline liquidpoll_published'><i class='fa fa-clock-o'></i> $time_ago</a>
			</div>
		</div>";
	
	endwhile;
	
	$big = 999999999;
	$paginate = array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, $paged ),
		'total' => $LIQUIDPOLL_Poll_query->max_num_pages
	);
			
	?><div class="paginate"> <?php echo paginate_links($paginate); ?> </div> <?php		
	
	wp_reset_query();
	
	else: ?><span><?php echo __('No Poll found', 'wp-poll'); ?></span> <?php
	endif; ?>
	
	
</div>