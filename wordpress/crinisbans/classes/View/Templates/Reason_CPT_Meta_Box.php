<div class="cb">
	<div class="columns is-multiline">
		<div class="column">
			<label for="cb-duration-picker"><?php esc_html_e( 'Ban Duration', 'crinisbans' )?></label>
			<input type="text" id="cb-duration-picker" name="cb-duration-picker" value="" />
			<input type="hidden" id="cb-duration" name="cb-duration" value="<?php echo esc_attr( $attrs['reason']->get_duration() )?>" />
		</div>
	</div>
</div>
