<?php
/**
 *  Frontpage
 */
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

			<form novalidate class="container p-3 card card-block bg-faded" id="rent_form_post" method="POST" enctype="multipart/form-data">

				<h2 class="mb-3 card-title">Тут вы можете заполнить форму отправки новой недвижимости</h2>

				<div class="form-row">

					<div class="col-lg-6">

						<?php // Тип недвижимости // wppw_cpt_rent_type ?>
						<?php
						$types = get_terms( [
							'taxonomy'	 => 'wppw_cpt_rent_type',
							'hide_empty' => false,
								] );
						if ( $types ) {
							?>
							<div class="form-group">
								<select class="custom-select" required name='wppw_cpt_rent_type'>
									<option value="">Выберите тип недвижимости</option>
									<?php foreach ( $types as $type ) { ?>
										<option value="<?= $type -> slug; ?>"><?= $type -> name; ?></option>
									<?php } ?>
								</select>
								<div class="invalid-feedback">Необходимо выбрать тип недвижимости</div>
							</div>
						<?php } ?>

						<div class="mb-3">
							<input type="text" class="form-control" required placeholder="Заголовок *" value="" name="post_title" id="post_title">
							<div class="invalid-feedback">Необходимо заполнить заголовок</div>
						</div>

						<div class="mb-3">
							<textarea required class="form-control" name="post_content" id="post_content" rows="3" placeholder="Описание *"></textarea>
							<div class="invalid-feedback">Необходимо заполнить описание</div>
						</div>

						<div class="mb-3">
							<input type="text" class="form-control" required placeholder="Площадь *" value="" name="meta[sq]" id="post_title">
							<div class="invalid-feedback">Необходимо заполнить площадь</div>
						</div>

						<div class="mb-3">
							<input type="text" class="form-control" required placeholder="Стоимость *" value="" name="meta[cost]" id="post_title">
							<div class="invalid-feedback">Необходимо заполнить стоимость</div>
						</div>

						<div class="mb-3">
							<input type="text" class="form-control" required placeholder="Адрес *" value="" name="meta[address]" id="post_title">
							<div class="invalid-feedback">Необходимо заполнить адрес</div>
						</div>

						<div class="mb-3">
							<input type="text" class="form-control" required placeholder="Жилая площадь *" value="" name="meta[live]" id="post_title">
							<div class="invalid-feedback">Необходимо заполнить жилая площадь</div>
						</div>

						<div class="mb-3">
							<input type="text" class="form-control" required placeholder="Этаж *" value="" name="meta[floor]" id="post_title">
							<div class="invalid-feedback">Необходимо заполнить этаж</div>
						</div>

					</div>

					<div class="col-lg-6">

						<?php // Медиа ?>
						<div class="h2 border_bottom mb-3 pb-2 text-center">Фото</div>


						<div class="input-group">
							<input type="file" required value="" placeholder="Скриншот работы" class="mb-3 gallery form-control" maxlength="100" name="gallery[]">
							<div class="invalid-feedback">Необходимо фото</div>
						</div>
						<div style="display:none" class="input-group">
							<input type="file"  value="" placeholder="Скриншот работы" class="mb-3 gallery form-control" maxlength="100" name="gallery[]"> &nbsp; &nbsp; <a href="#" class="cross" style='color:#F00;'>❌</a>
						</div>
						<a href='#' id='add_photo'>Добавить ещё фото</a>



					</div>

				</div>

				<div class="form-submit text-center">
					<?php wp_nonce_field( $action = WPPW_NONCE, $name = 'nonce' ); ?>
					<input type="submit" value="Отправить" class="btn btn-primary" id="submit" name="submit"> 
				</div>

			</form>

		</div><!-- .row -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
