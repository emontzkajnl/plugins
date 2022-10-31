<?php
/**
 * Sample template for displaying single magazine posts.
 * Save this file as as single-magazine.php in your current theme.
 *
 * This sample code was based off of the Starkers Baseline theme: http://starkerstheme.com/
 */

get_header(); ?>
<!-- MAIN CONTNENT -->
<div id="content-wrapper">
	<div id="content-main" class="content-full">
		<div id="home-main" class="home-full">
			<div id="dm-container">
				<div id="magazine-content">
					<div id="post-area" class="indv-mag">
					<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
						<h1 class="story-title"><?php the_title(); ?></h1>
							<?php the_content(); ?>
							<?php global $post; 
								  //loading all fields of the magazine entry
								$magazine = get_post_custom($post->ID); 
								  //loading all fields of device detection entry
							// $args = array( 'numberposts' => 1, 'category' => 114, 'post_status' => 'publish' );
							// $dd = get_posts( $args );
								// $device_detection = get_post_custom($dd[0]->ID);
								  ?>
						<div class="clear"></div>
						<hr size="1px">
						<div id="magazine">
							<?php $calameo_id = get_post_custom_values('calameo_id'); ?>
							<?php $calameo_page = (get_query_var('dmpage')) ? get_query_var('dmpage') : 1;  ?>
							<iframe style="margin: 0 auto;" src="//v.calameo.com/?bkcode=<?php echo $calameo_id[0] ; ?>&page=<?php echo $calameo_page ; ?>" width="100%" height="700" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen"></iframe>
						</div><!-- End Magazine -->
						<hr size="1px">
						<div class="magazine_copy"><?php echo $magazine[magazine_copy][0] ; ?></div>    

						<?php endwhile; ?>

						<div class="mag-grid">
							<h3 class="home-widget-header category-title">Read Past Issues</h3>
						  	<?php 
							$args = array( 'post_type' => 'magazine', 'posts_per_page' => -1 );
							$loop = new WP_Query( $args );
							while ( $loop->have_posts() ) : $loop->the_post();
								echo '<div class="mag-cover">';
								echo '<a href="';
								the_permalink();
								echo '">';
								the_post_thumbnail( array(180,243) );
								echo '</a>';
								echo '<a href="';
								the_permalink();
								echo '">';
								the_title();
								echo '</a>';
								echo '</div>';
							endwhile;
						  	?>
						</div>
						<div class="clear"></div>
					<?php else : ?>
						<div class="post">
							<h2><?php _e('not_found'); ?></h2>
						</div>
					<?php endif; ?>
					</div><!-- END POST AREA -->
				</div><!-- END MAGAZINE CONTENT -->
			</div><!-- END DM CONTAINER -->
		</div><!-- END HOME MAIN -->
	</div><!-- CONTENT MAIN -->
</div><!-- END CONTENT WRAPPER -->
</div><!--main-wrapper-->
<?php get_footer(); ?>