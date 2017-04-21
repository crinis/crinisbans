<div class="cb">
    <div class="tile is-ancestor">
	    <div class="tile is-vertical">
            <div class="tile is-parent">
                <div class="tile is-child">
                    <?php echo esc_html( $attrs['group']->get_excerpt() ); ?>
				</div>
			</div>
			<div class="tile">
				<div class="tile is-parent">
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Members of this group','crinisbans' ) ?></h5>
						<div class="columns is-multiline">
							<?php
							foreach ( $attrs['admins'] as $admin ) {
								?>
								<div class="column">
									<div class="box columns is-multiline is-gapless has-text-centered">
										<div class="column is-full column-bottom-margin">
											<?php echo esc_html( $admin->get_title() )?>
										</div>
										<div class="column is-full">
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
											<a class="button is-info box-icon-link" title="<?php esc_attr_e( 'Visit detailed page of this member','crinisbans' ) ?>" href="<?php echo esc_url( $admin->get_permalink() )?>">
												<span class="fa fa-arrow-circle-right">
													<span class="sc-content">
														<?php esc_html_e( 'Visit detailed page of this member', 'crinisbans' ); ?>
													</span>
												</span>
											</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
