<?php
	$featured_slider_class = '';
	if ( 'on' == et_get_option('lucid_slider_auto') ) $featured_slider_class .= ' et_slider_auto et_slider_speed_' . et_get_option('lucid_slider_autospeed');
	if ( 'slide' == et_get_option('lucid_slider_effect') ) $featured_slider_class .= ' et_slider_effect_slide';
?>
<div id="featured_section">
	<div id="featured" class="<?php echo esc_attr( 'flexslider' . $featured_slider_class ); ?>">
		<ul class="slides">
		<?php
			$info = array();

			$featured_cat = et_get_option('lucid_feat_cat');
			$featured_num = et_get_option('lucid_featured_num');

			if ( 'false' == et_get_option('lucid_use_pages','false') ) {
				$featured_query = new WP_Query( apply_filters( 'et_featured_post_args', array(
					'posts_per_page' 	=> intval( $featured_num ),
					'cat' 				=> (int) get_catId( et_get_option('lucid_feat_posts_cat') )
				) ) );
			} else {
				global $pages_number;

				if ( '' != et_get_option('lucid_feat_pages') ) $featured_num = count( et_get_option('lucid_feat_pages') );
				else $featured_num = $pages_number;

				$et_featured_pages_args = array(
					'post_type' => 'page',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => (int) $featured_num,
				);

				if ( is_array( et_get_option( 'lucid_feat_pages', '', 'page' ) ) )
					$et_featured_pages_args['post__in'] = (array) array_map( 'intval', et_get_option( 'lucid_feat_pages', '', 'page' ) );

				$featured_query = new WP_Query( apply_filters( 'et_featured_page_args', $et_featured_pages_args ) );
			}

			$i = 1;

			while ( $featured_query->have_posts() ) : $featured_query->the_post();
				$category = get_the_category();

				$info[$i]['date'] = get_the_time( 'D' ) . '<span>' . get_the_time( 'd' ) . '</span>';
				$info[$i]['title'] = ( $custom_title = get_post_meta( $post->ID, 'featured_title', true ) ) && '' != $custom_title ? $custom_title : apply_filters( 'the_title', get_the_title() );
				if ( 'false' == et_get_option('lucid_use_pages','false') )
					$info[$i]['postinfo'] = __( 'Posted in ', 'Lucid' ) . $category[0]->cat_name;
			?>
				<li class="slide">
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php
							$width = apply_filters( 'slider_image_width', 960 );
							$height = apply_filters( 'slider_image_height', 360 );
							$title = get_the_title();
							$thumbnail = get_thumbnail($width,$height,'',$title,$title,false,'Featured');
							$thumb = $thumbnail["thumb"];

							print_thumbnail($thumb, $thumbnail["use_timthumb"], $title, $width, $height, '');
						?>
						<span class="overlay"></span>
					</a>
				</li> <!-- end .slide -->
		<?php
				$i++;
			endwhile; wp_reset_postdata();
		?>
		</ul>
	</div> <!-- end #featured -->

	<?php if ( $featured_num < 4 ){ ?>
		<div id="switcher-container">
			<ul id="switcher" class="clearfix">
				<?php for ( $i = 1; $i <= $featured_num; $i++ ) { ?>
					<li<?php if ( 1 == $i ) echo ' class="active-slide"'; if ( 3 == $i ) echo ' class="last"'; ?>>
						<div class="switcher-content">
							<span class="post-meta"><?php echo $info[$i]['date']; ?></span>
							<h2><?php echo esc_html( $info[$i]['title'] ); ?></h2>
							<?php if ( 'false' == et_get_option('lucid_use_pages','false') ) : ?>
								<p class="meta-info"><?php echo esc_html( $info[$i]['postinfo'] ); ?></p>
							<?php endif; ?>
						</div> <!-- end .switcher-content -->
					</li>
				<?php } ?>
			</ul>
		</div> <!-- end #switcher-container -->
	<?php } ?>
</div> <!-- end #featured_section -->