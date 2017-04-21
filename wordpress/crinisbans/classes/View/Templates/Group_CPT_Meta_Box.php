<div class="cb">
	<div class="columns is-multiline">
		<div class="column">
			<label for="cb-immunity"><?php esc_html_e( 'Immunity', 'crinisbans' )?></label>
			<input type="text" id="cb-immunity" name="cb-immunity" value="<?php echo esc_attr( $attrs['group']->get_immunity() ) ?>" />
		</div>
		<div class="column">
			<h3><?php esc_html_e( 'Flags','crinisbans' )?></h3>
			<?php
			$current_flags = $attrs['group']->get_flags();
			foreach ( $attrs['all_flags'] as $key => $value ) {
				if ( 1 === $current_flags[ $key ] ) {
					$flag_enabled = true;
				} else {
					$flag_enabled = false;
				}
				?>
				<input <?php checked( $flag_enabled )?> value="<?php echo esc_attr( $key )?>" type="checkbox" id="cb-flags_<?php echo esc_attr( $key )?>" name="cb-flags[]" />
				<label for="cb-flags_<?php echo esc_attr( $key )?>"><?php echo esc_html( $this->util->explain_flag( $key ) )?></label>
				<br>
				<?php
			}
			?>
		</div>
	</div>
</div>
