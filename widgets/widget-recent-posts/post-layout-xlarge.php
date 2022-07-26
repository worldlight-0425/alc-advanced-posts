<?php
/**
 * Recent Posts - XLarge
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.2.0
 * @version   2.1.3
 */

?>

<div <?php post_class( $post_classes ); ?>>

	<?php if ( $show_thumb ) : ?>
		<figure class="<?php echo esc_attr( $thumb_classes ); ?>">
			<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( $post_thumb_size, array( 'class' => '' )); ?>
			<?php else : ?>
				<img src="<?php echo get_theme_file_uri( '/assets/images/placeholder-400x400.jpg' ); ?>" alt="" />
			<?php endif; ?>
			</a>
			<?php do_action( 'alchemists_after_post_featured_img' ); ?>
		</figure>
	<?php endif; ?>

	<a href="<?php the_permalink(); ?>" class="posts__cta"></a>

	<div class="posts__inner">

		<?php if ( $categories_toggle ) : ?>
			<?php alchemists_post_category_labels(); ?>
		<?php endif; ?>

		<h6 class="posts__title" title="<?php the_title_attribute(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
		<time datetime="<?php esc_attr( the_time('c') ); ?>" class="posts__date">
			<?php the_time( get_option('date_format') ); ?>
		</time>

		<?php if ( 'default' == $meta_on || 'enable' == $meta_on ) : ?>
			<footer class="posts__footer card__footer">

				<?php if ( $post_author ) : ?>
					<div class="post-author">
						<figure class="post-author__avatar">
							<?php echo get_avatar( get_the_author_meta('email'), '24' ); ?>
						</figure>
						<div class="post-author__info">
							<h4 class="post-author__name">
								<?php the_author(); ?>
							</h4>
						</div>
					</div>
				<?php endif; ?>

				<div class="post__meta meta">
					<?php
					if ( $post_views ) {
						if ( function_exists( 'alchemists_getPostViews' ) ) {
							echo '<div class="meta__item meta__item--views">' . alchemists_getPostViews(get_the_ID()) . '</div>';
						}
					}
					if ( $post_likes ) {
						if ( function_exists( 'get_simple_likes_button') ) {
							echo get_simple_likes_button( get_the_ID() );
						}
					}
					if ( $post_comments ) {
						alchemists_entry_comments();
					}
					?>
				</div>
			</footer>
		<?php endif; ?>

	</div>

</div>
