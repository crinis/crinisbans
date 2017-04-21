<div class="cb">
	<div class="columns is-multiline">
		<div class="column">
			<label for="cb-server-address"><?php esc_html_e( 'Server Address', 'crinisbans' )?></label>
			<input type="text" id="cb-server-address" name="cb-server-address" value="<?php echo esc_attr( $attrs['server']->get_address() ) ?>" />
		</div>
		<div class="column">
			<label for="cb-server-port"><?php esc_html_e( 'Server Port', 'crinisbans' )?></label>
			<input type="text" id="cb-server-port" name="cb-server-port" value="<?php echo esc_attr( $attrs['server']->get_port() ) ?>" />
		</div>
		<div class="column">
			<label for="cb-gotv-address"><?php esc_html_e( 'GOTV Address', 'crinisbans' )?></label>
			<input type="text" id="cb-gotv-address" name="cb-gotv-address" value="<?php echo esc_attr( $attrs['server']->get_gotv_address() ) ?>" />
		</div>
		<div class="column">
			<label for="cb-gotv-port"><?php esc_html_e( 'GOTV Port', 'crinisbans' )?></label>
			<input type="text" id="cb-gotv-port" name="cb-gotv-port" value="<?php echo esc_attr( $attrs['server']->get_gotv_port() ) ?>" />
		</div>
		<div class="column">
			<label for="cb-rcon-password"><?php esc_html_e( 'RCON Password', 'crinisbans' )?></label>
			<input type="password" id="cb-rcon-password" name="cb-rcon-password" value="<?php echo esc_attr( $attrs['server']->get_rcon_password() ) ?>" />
		</div>
		<?php $this->viewhelper->object_list( 'cb-server-groups','Server Groups',$attrs['all_server_groups'],$attrs['server']->get_server_group_post_ids() ) ?>
	</div>
</div>
