<?php
/**
 * The template for displaying Single Team
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.0.0
 * @version   2.0.0
 */

// get post category class
$post_class = alchemists_post_category_class();
?>

<li class="posts__item <?php echo esc_attr( $post_class ); ?>">

	<?php if ( has_post_thumbnail() && $show_thumb ) : ?>
	<figure class="posts__thumb posts__thumb--hover">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $post_thumb_size, array( 'class' => '' ) ); ?>
		</a>
	</figure>
	<?php endif; ?>

	<div class="posts__inner">
		<?php
		if ( $categories_toggle ) {
			alchemists_post_category_labels();
		}
		?>

		<h6 class="posts__title" title="<?php the_title_attribute(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
		<time datetime="<?php the_time('c'); ?>" class="posts__date">
			<?php the_time( get_option('date_format') ); ?>
		</time>
		<?php if ( $show_excerpt ) : ?>
			<div class="posts__excerpt">
				<?php echo alchemists_string_limit_words( get_the_excerpt(), $excerpt_size); ?>
			</div>
		<?php endif; ?>
	</div>
</li>
