<div class="cb">
	<div class="columns is-multiline">
		<div class="column">
			<label for="cb-player-id"><?php esc_html_e( 'Steam ID', 'crinisbans' )?></label>
			<input type="text" id="cb-player-id" name="cb-player-id" value="<?php echo esc_attr( $attrs['ban']->get_steam_id_64() ) ?>" />
		</div>
		<div class="column">
			<label for="cb-reason"><?php esc_html_e( 'Ban Reason', 'crinisbans' )?></label>
			<select name="cb-reason" id="cb-ban-reason">
				<?php
				foreach ( $attrs['all_reasons'] as $reason ) {
					$active = $reason->get_post_id() === $attrs['reason']->get_post_id();
					?>
					<option <?php selected( $active ) ?>value="<?php echo esc_attr( $reason->get_post_id() )?>"><?php echo esc_html( $reason->get_title() )?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
</div>
