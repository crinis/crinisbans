<div class="cb">
    <div class="columns is-multiline">
    <?php
	$ban_loop = $this->util->get_loop( $attrs['ban_post_ids'],true );
	while ( $ban_loop->have_posts() ) : $ban_loop->the_post();
		$ban = $this->ban_repository->get( get_the_ID() );
		$reason = $this->reason_repository->get( $ban->get_reason_post_id() );
		?>
		<div class="column">
			<div class="box">
				<div class="columns is-multiline is-gapless has-text-centered">
					<div class="column is-full">
						<h3><?php echo esc_html( $ban->get_title() )?></h3>
					</div>
					<div class="column is-full column-bottom-margin">
						<?php echo esc_html( $reason ? $reason->get_title() : null )?>
					</div>
					<div class="column is-full">
						<a class="button is-info box-icon-link" target="_blank" title="<?php esc_attr_e( 'Visit Steam Community Profile','crinisbans' ) ?>" href="<?php echo esc_attr( $this->util->get_url_from_steam_id_64( $ban->get_steam_id_64() ) ) ?>">
							<span class="fa fa-steam-square">
								<span class="sc-content">
									<?php esc_html_e( 'Visit Steam Community Profile','crinisbans' );?>
								</span>
							</span>
						</a>
						<a class="button is-info box-icon-link" title="<?php esc_attr_e( 'Visit detailed page for this ban','crinisbans' ) ?>" href="<?php echo esc_url( $ban->get_permalink() )?>">
							<span class="fa fa-arrow-circle-right">
								<span class="sc-content">
									<?php esc_html_e( 'Visit detailed page for this ban','crinisbans' ); ?>
								</span>
							</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
		<div class="column is-full">
			<div class="navigation">
				<div class="alignleft"><?php previous_posts_link( '&laquo; Previous' ) ?></div>
				<div class="alignright"><?php
				next_posts_link( 'More &raquo;',$ban_loop->max_num_pages );
				wp_reset_postdata();?>
				</div>
			</div>
		</div>
	</div>
</div>
