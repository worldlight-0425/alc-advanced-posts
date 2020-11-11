<?php
/**
 * Recent Posts - Large
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.2.0
 * @version   2.0.9
 */

?>

<div <?php post_class( $post_classes ); ?>>

	<?php if ( has_post_thumbnail() && $show_thumb ) : ?>
	<figure class="<?php echo esc_attr( $thumb_classes ); ?>">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $post_thumb_size, array( 'class' => '' )); ?>
		</a>
		<?php do_action( 'alchemists_after_post_featured_img' ); ?>
	</figure>
	<?php endif; ?>

	<div class="posts__inner">
		<?php if ( $categories_toggle ) : ?>
			<?php alchemists_post_category_labels(); ?>
		<?php endif; ?>

		<h6 class="posts__title" title="<?php the_title_attribute(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
		<time datetime="<?php esc_attr( the_time('c') ); ?>" class="posts__date">
			<?php the_time( get_option('date_format') ); ?>
		</time>

		<?php if ( 'default' == $excerpt_on || 'enable' == $excerpt_on ) : ?>
			<div class="posts__excerpt">
				<?php echo alchemists_string_limit_words( get_the_excerpt(), $excerpt_size); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( 'default' == $meta_on || 'enable' == $meta_on ) : ?>
		<div class="posts__footer">

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
		</div>
	<?php endif; ?>

</div>
