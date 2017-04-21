<div class="cb">
    <div class="tile is-ancestor">
        <div class="tile is-vertical">
            <div class="tile is-parent">
                <div class="tile is-child">
                    <?php echo esc_html( $attrs['ban']->get_excerpt() ) ?>
				</div>
			</div>
			<div class="tile">
				<div class="tile is-parent is-vertical">
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Steam ID','crinisbans' ) ?></h5>
						<?php echo esc_html( $attrs['ban']->get_steam_id() );?>
						<a class="button is-info list-icon-link" target="_blank" title="<?php esc_attr_e( 'Visit Steam Community Profile','crinisbans' ) ?>" href="<?php echo esc_url( $this->util->get_url_from_steam_id( $attrs['ban']->get_steam_id() ) ) ?>">
							<span class="fa fa-steam-square">
								<span class="sc-content">
									<?php esc_html_e( 'Visit Steam Community Profile','crinisbans' );?>
								</span>
							</span>
						</a>
					</div>
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Steam ID 64','crinisbans' ) ?></h5>
						<?php echo esc_html( $attrs['ban']->get_steam_id_64() ); ?>
						<a class="button is-info list-icon-link" target="_blank" title="<?php esc_attr_e( 'Visit Steam Community Profile','crinisbans' ) ?>" href="<?php echo esc_url( $this->util->get_url_from_steam_id_64( $attrs['ban']->get_steam_id_64() ) ) ?>">
							<span class="fa fa-steam-square">
								<span class="sc-content">
									<?php esc_html_e( 'Visit Steam Community Profile','crinisbans' );?>
								</span>
							</span>
						</a>
					</div>
				</div>
				<div class="tile is-parent">
					<div class="tile is-child">
						<div class="columns is-multiline is-gapless box has-text-centered">
							<div class="column is-full column-bottom-margin">
								<?php echo esc_html( $attrs['reason']->get_title() )?>
							</div>
							<div class="column is-full">
								<a class="button is-info box-icon-link" title="<?php esc_attr_e( 'Visit detailed page of this ban reason','crinisbans' ) ?>" href="<?php echo esc_url( $attrs['reason']->get_permalink() )?>">
									<span class="fa fa-arrow-circle-right">
										<span class="sc-content">
											<?php esc_html_e( 'Visit detailed page of this ban reason' ); ?>
										</span>
									</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="tile is-parent is-vertical">
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Banned at','crinisbans' ) ?></h5>
						<?php echo esc_html( $this->util->get_formatted_datetime( $attrs['ban']->get_date_gmt() ) ) ?>
					</div>
					<div class="tile is-child">
						<h5 class="is-detail-heading"><?php esc_html_e( 'Banned until','crinisbans' ) ?></h5>
						<?php echo esc_html( $attrs['ban_end'] ) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
