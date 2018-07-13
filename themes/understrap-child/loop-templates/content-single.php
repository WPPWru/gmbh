<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

			<?php understrap_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post -> ID, 'large' ); ?>

	<div class="entry-content">


		<?php the_content(); ?>
		<h2>Подробная информация:</h2>

		<?php
		// Поля
		extract( get_fields() );
		?>

		<ul>
			<?php if ( $cost ) { ?><li><b>Цена, р.: </b><?= $cost; ?></li><?php } ?>
			<?php if ( $address ) { ?><li><b>Адрес, м2: </b><?= $address; ?></li><?php } ?>
			<?php if ( $sq ) { ?><li><b>Площадь, м2: </b><?= $sq; ?></li><?php } ?>
			<?php if ( $live ) { ?><li><b>Жилая площадь, м2: </b><?= $live; ?></li><?php } ?>
			<?php if ( $floor ) { ?><li><b>Этаж: </b><?= $floor; ?></li><?php } ?>
		</ul>

		<div class="row" id="lightgallery">

			<?php
			if ( $photos = get_field( 'gallery' ) ) {
				
				foreach ( $photos as $photo ) {
					?>
					<a href="<?= $photo['url']; ?>"><img src="<?= $photo['url']; ?>"></a>
					<?php
				} unset( $photo );
			}
			?>
		</div>


		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
			'after'	 => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
