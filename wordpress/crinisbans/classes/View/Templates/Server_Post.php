<div class="cb">
    <div class="tile is-ancestor">
        <div class="tile is-vertical">
            <?php if ( $attrs['server']->has_excerpt() ) { ?>
				<div class="tile is-parent">
					<div class="tile is-child">
						<?php echo esc_html( $attrs['server']->get_excerpt() )?>
					</div>
				</div>
			<?php } ?>
			<div class="tile">
				<div class="tile is-parent">
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Server Address','crinisbans' ) ?></h5>
						<?php echo esc_html( $attrs['server']->get_address() . ':' . $attrs['server']->get_port() ); ?>
						<a class="button is-info list-icon-link" target="_blank" title="<?php esc_attr_e( 'Connect to gameserver','crinisbans' ) ?>" href="<?php echo esc_attr( $attrs['server']->get_address_connect() )?>">
							<span class="fa fa-gamepad">
								<span class="sc-content">
									<?php esc_html_e( 'Connect to gameserver','crinisbans' );?>
								</span>
							</span>
						</a>
					</div>
				</div>
				<div class="tile is-parent">
					<?php if ( $attrs['server']->has_gotv() ) { ?>
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'GOTV Server Address','crinisbans' ) ?></h5>
						<?php echo esc_html( $attrs['server']->get_gotv_address() . ':' . $attrs['server']->get_gotv_port() ); ?>
						<a class="button is-info list-icon-link" target="_blank" title="<?php esc_attr_e( 'Connect to GOTV','crinisbans' ) ?>" href="<?php echo esc_attr( $attrs['server']->get_gotv_address_connect() )?>">
							<span class="fa fa-television">
								<span class="sc-content">
									<?php esc_html_e( 'Connect to GOTV','crinisbans' );?>
								</span>
							</span>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="tile is-parent">
				<div class="tile is-child">
					<h5 class="is-detail-heading"><?php esc_html_e( 'Member of','crinisbans' ) ?></h5>
					<div class="columns is-multiline">
						<?php
						foreach ( $attrs['server_groups'] as $server_group ) {
							?>
							<div class="column">
								<div class="box columns is-multiline is-gapless has-text-centered">
									<div class="column is-full column-bottom-margin">
										<?php echo esc_html( $server_group->get_title() )?>
									</div>
									<div class="column is-full">
										<a class="button is-info box-icon-link" title="<?php esc_attr_e( 'Visit detailed page for this server group','crinisbans' ) ?>" href="<?php echo esc_url( $server_group->get_permalink() )?>">
											<span class="fa fa-arrow-circle-right">
												<span class="sc-content">
													<?php esc_html_e( 'Visit detailed page for this server group', 'crinisbans' ); ?>
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
