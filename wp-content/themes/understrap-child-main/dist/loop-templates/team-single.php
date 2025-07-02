<div class="col-lg-10">
	<?php

		if( have_rows('team_rep') ): ?>
			<div class="row max-1360">
				<?php
				while( have_rows('team_rep') ): the_row();

					$name = get_sub_field('name');
					$image = get_sub_field('bild');
					$position = get_sub_field('position');
					?>

					<div class="col-sm-6 col-lg-4 col-xl-3 mb-5 ps-0 pe-0">
						<div class="card mb-4">
							<div class="card-body bg-blue p-0">
								<?php if( get_sub_field('bild') ): ?>
									<img class="image img-fluid" src="<?php echo $image?>" />
									<?php else: ?>
									<div class="initials-bg">
										<div class="inner">
											<?php echo get_sub_field('initialen'); ?>
										</div>
									</div>
								<?php endif; ?>	
								<h5 class="card-title mb-0"><?php echo $name?></h5>
								<p class="card-text"><?php echo $position?></p>
							</div>
						</div>
					</div>
				<?php 
				endwhile;?>
					<div class="col-lg-4 text-white teaser mb-5">
						<p>Je nach Projekt und Anforderungen stellen wir ein Team für Ihr Projekt zusammen..</p>
					</div>
			</div>
			<?php
		endif;
	?>
</div>