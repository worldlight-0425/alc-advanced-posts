<?php
/**
 * Recent Posts - Large
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     2.0.0
 * @version   2.1.3
 */

?>

<div class="post-grid__item col-12 col-sm-6 col-lg-12">
	<div <?php post_class( $post_classes ); ?>>

		<figure class="<?php echo esc_attr( $thumb_classes ); ?>">
			<a href="<?php the_permalink(); ?>">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( $post_thumb_size, array( 'class' => '' ));
				} else {
					echo '<img src="' . get_theme_file_uri( '/assets/images/placeholder-380x270.jpg' ) . '" alt="" />';
				}
				?>
			</a>
		</figure>

		<div class="posts__inner card__content">
			<?php
			if ( $categories_toggle ) {
				alchemists_post_category_labels( 'posts__cat', 'catvideos' );
			}
			?>
			<h6 class="posts__title posts__title--color-hover" title="<?php the_title_attribute(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>

			<?php alchemists_entry_footer( false, true); ?>
		</div>

	</div>
</div>
