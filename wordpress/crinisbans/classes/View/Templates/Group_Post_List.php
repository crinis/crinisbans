<div class="cb">
    <div class="columns is-gapless">
        <div class="column is-full">
        <?php
		$group_loop = $this->util->get_loop( $this->group_repository->get_all_post_ids(),false,'ASC','title' );
		while ( $group_loop->have_posts() ) : $group_loop->the_post();
			$group = $this->group_repository->get( get_the_ID() );
			?>
			<div class="columns is-gapless">
				<div class="column">
					<h3>
						<a class="button is-info" title="<?php esc_attr_e( 'Visit detailed page of this group','crinisbans' ) ?>" href="<?php echo esc_url( $group->get_permalink() )?>">
							<?php echo esc_html( $group->get_title(),'crinisbans' ) ?>
							<span class="fa fa-arrow-circle-right">
							</span>
						</a>
					</h3>
				</div>
			</div>
			<div class="columns is-multiline">
			<?php
			$admin_loop = $this->util->get_loop( $this->admin_repository->get_post_ids_by_group( $group ),false,'ASC','title' );
			while ( $admin_loop->have_posts() ) : $admin_loop->the_post();
				$admin = $this->admin_repository->get( get_the_ID() );
				?>
				<div class="column is-narrow">
					<div class="box">
						<div class="columns is-multiline is-gapless has-text-centered">
							<div class="column is-full">
								<h5><?php echo esc_html( $admin->get_title() )?></h5>
							</div>
							<div class="column is-full column-bottom-margin">
								<?php echo get_avatar( $admin->get_user_id() ); ?>
							</div>
							<div class="column is-full">
								<a class="button is-info box-icon-link" target="_blank" title="<?php esc_attr_e( 'Visit Steam Community Profile','crinisbans' ) ?>" href="<?php echo esc_url( $this->util->get_url_from_steam_id_64( $admin->get_steam_id_64() ) ) ?>">
									<span class="fa fa-steam-square">
										<span class="sc-content">
											<?php esc_html_e( 'Visit Steam Community Profile','crinisbans' );?>
										</span>
									</span>
								</a>
								<a class="button is-info box-icon-link" title="<?php esc_attr_e( 'Visit detailed page of this admin','crinisbans' ) ?>" href="<?php echo esc_url( $admin->get_permalink() )?>">
									<span class="fa fa-arrow-circle-right">
										<span class="sc-content">
											<?php esc_html_e( 'Visit detailed page of this admin','crinisbans' ); ?>
										</span>
									</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			?>
			</div>
		<?php
		endwhile;
		?>
		</div>
	</div>
</div>
