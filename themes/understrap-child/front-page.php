<?php
/**
 *  Frontpage
 */
acf_form_head();

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php if ( is_front_page() && is_home() ) : ?>
	<?php get_template_part( 'global-templates/hero' ); ?>
<?php endif; ?>

<div class="wrapper" id="index-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<div class="col-lg-8">

				<h2>Доступная недвижимость</h2>


				<?php
				// Список недвижимости

				foreach ( [
			[
				'wppw_cpt_rent_type' => 'kvartira',
				'name'				 => 'Квартиры',
			],
			[
				'wppw_cpt_rent_type' => 'ofis',
				'name'				 => 'Офисы',
			],
			[
				'wppw_cpt_rent_type' => 'chastnyj-dom',
				'name'				 => 'Частные дома',
			],
				] as $type ) {
					
					$p = get_posts( [
						'post_type'			 => 'wppw_cpt_rent',
						'posts_per_page'	 => 10,
						'wppw_cpt_rent_type' => $type['wppw_cpt_rent_type'],
							] );

					if ( $p ) {

						printf( '<h2>%s</h2>', $type['name'] );

						foreach ( $p as $post ) {

							setup_postdata( $post );
							?>

							<figure class="figure border p-2 ">
								<a href="<?= get_permalink(); ?>">
									<img src="<?= kama_thumb_src( 'w=340 &h=300' ); ?>" class="figure-img img-fluid rounded" alt="">
									<figcaption class="figure-caption text-center"><?= get_the_title(); ?></figcaption>
								</a>
							</figure>


							<?php
						} wp_reset_postdata();
					}
				} unset( $type );
				?>




			</div>
			<div class="col-lg-4">

				<h2>В городах</h2>
				<?php
				// Список недвижимости
				$cities = get_terms( [
					'taxonomy'	 => 'wppw_cpt_rent_city',
					'hide_empty' => false,
						] );


				if ( $cities ) {
					foreach ( $cities as $city ) {


						$src = get_field( 'photo', 'wppw_cpt_rent_city_' . $city -> term_id );
						$src = $src['url'];
						?>

						<figure class="figure border p-2 col-lg-12 text-center" style="background: rgba(150,150,250,0.2);">
							<a href="<?= get_term_link( $city -> term_id ); ?>">
								<img src="<?= kama_thumb_src( 'w=350 &h=300 &src=' . $src ); ?>" class="figure-img img-fluid rounded" alt="">
								<figcaption class="figure-caption text-center"><?= $city -> name ?></figcaption>
							</a>
						</figure>


						<?php
					} wp_reset_postdata();
				}
				?>



			</div>



		</div><!-- .row -->

		<div class="row">
			<h2>Тут вы можете заполнить форму отправки новой недвижимости</h2>
			<?php acf_form( 'wppw_cpt_rent' ); ?>
		</div>

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
