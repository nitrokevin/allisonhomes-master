<div class="swiper slide-carousel">
    <div class="swiper-button-prev" aria-label="Go to previous slide"></div>
    <div class="swiper-button-next" aria-label="Next slide"></div>
	  <!-- If we need pagination -->
  <div class="swiper-pagination"></div>

    <div class="swiper-wrapper">
        	<?php if ((have_rows('repeater_content_carousel') )) { 
            while ((have_rows('repeater_content_carousel')) ){ the_row();
                $carousel_heading = get_sub_field('carousel_heading');
                $carousel_image = get_sub_field('carousel_image');
                $carousel_background_color = get_sub_field('carousel_background_color');
                $carousel_content = get_sub_field('carousel_content');
        
                ?>
                <div class="swiper-slide <?php echo esc_attr($carousel_background_color); ?>">
                 <div class="info">
				<h3><?php echo $carousel_heading ?></h3>
				<span><?php echo $carousel_content; ?></span>
			
					<?php if ($carousel_image): ?>
						<div class="image">
							<?php 
							$image_src = wp_get_attachment_image_url($carousel_image['id'], 'fp-large');
							$image_srcset = wp_get_attachment_image_srcset($carousel_image['id'], 'fp-large');
							?>
							<img 
							src="<?php echo esc_url($image_src); ?>"
							srcset="<?php echo esc_attr($image_srcset); ?>"
							sizes="(max-width: 100vw) 100%"
							alt="<?php echo esc_attr($carousel_image['alt']); ?>" 
							/>
						</div>
					<?php endif; ?>
			 <?php if ( get_sub_field('carousel_button_url') && get_sub_field('carousel_button_text') ) : ?>
                <div class="carousel-button">
                    <a href="<?php the_sub_field('carousel_button_url'); ?>" class="primary">
                        <?php the_sub_field('carousel_button_text'); ?>
                    </a>
                </div>
            <?php endif; ?>
				 </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
