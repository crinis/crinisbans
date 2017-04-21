<div class="cb">
    <div class="tile is-ancestor">
        <div class="tile is-vertical">
            <?php if ( $attrs['admin']->has_excerpt() ) { ?>
				<div class="tile is-parent">
					<div class="tile is-child">
						<?php echo esc_html( $attrs['admin']->get_excerpt() )?>
					</div>
				</div>
			<?php } ?>
			<div class="tile">
				<div class="tile is-parent is-vertical">
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Steam ID','crinisbans' ) ?></h5>
						<ul>
						<?php
						foreach ( $attrs['admin']->get_steam_ids() as $steam_id ) {
							?>
							<li>
								<?php echo esc_html( $steam_id );?>
								<a class="button is-info list-icon-link" target="_blank" title="<?php esc_attr_e( 'Visit Steam Community Profile','crinisbans' ) ?>" href="<?php echo esc_attr( $this->util->get_url_from_steam_id( $steam_id ) ) ?>">
									<span class="fa fa-steam-square">
										<span class="sc-content">
											<?php esc_html_e( 'Visit Steam Community Profile','crinisbans' );?>
										</span>
									</span>
								</a>
							</li>
							<?php
						}
						?>
						</ul>
					</div>
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Steam ID 64','crinisbans' ) ?></h5>
						<ul>
						<?php
						foreach ( $attrs['admin']->get_steam_ids_64() as $steam_id_64 ) {
							?>
							<li>
								<?php echo esc_html( $steam_id_64 ); ?>
								<a class="button is-info list-icon-link" target="_blank" title="<?php esc_attr_e( 'Visit Steam Community Profile','crinisbans' ) ?>" href="<?php echo esc_attr( $this->util->get_url_from_steam_id_64( $steam_id_64 ) ) ?>">
									<span class="fa fa-steam-square">
										<span class="sc-content">
											<?php esc_html_e( 'Visit Steam Community Profile','crinisbans' );?>
										</span>
									</span>
								</a>
							</li>
							<?php
						}
						?>
						</ul>
					</div>
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Responsible since','crinisbans' ) ?></h5>
						<?php echo esc_html( $this->util->get_formatted_datetime( $attrs['admin']->get_date_gmt() ) ) ?>
					</div>
				</div>
				<div class="tile is-parent">
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Member of','crinisbans' ) ?></h5>
						<div class="columns is-multiline">
							<?php
							foreach ( $attrs['groups'] as $group ) {
							?>
								<div class="column">
									<div class="box columns is-multiline is-gapless has-text-centered">
										<div class="column is-full column-bottom-margin">
											<?php echo esc_html( $group->get_title() ) ?>
										</div>
										<div class="column is-full">
											<a class="button is-info box-icon-link" title="<?php esc_attr_e( 'Visit detailed page of this group','crinisbans' ) ?>" href="<?php echo esc_url( $group->get_permalink() )?>">
												<span class="fa fa-arrow-circle-right">
													<span class="sc-content">
														<?php esc_html_e( 'Visit detailed page of this group', 'crinisbans' ); ?>
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
