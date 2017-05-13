<div class="cb">
	<div class="columns is-multiline">
		<div class="column">
			<label for="cb-player-ids"><?php esc_html_e( 'Steam IDs', 'crinisbans' )?></label>
			<input type="text" id="cb-player-ids" name="cb-player-ids" value="<?php echo esc_attr( implode( ',', $attrs['admin']->get_steam_ids_64() ) ) ?>" />
		</div>
		<div class="column">
			<label for="cb-user-id"><?php esc_html_e( 'WordPress user', 'crinisbans' )?></label>
			<select id="cb-user-id" name="cb-user-id">
				<?php
				foreach ( get_users( 'orderby=nicename' ) as $user ) {
					$is_current_user = $user->ID === $attrs['admin']->get_user_id();
				?>
					<option <?php selected( $is_current_user ) ?> value="<?php echo esc_attr( $user->ID ) ?>"><?php echo esc_html( $user->user_login . '(' . $user->ID . ')' ) ?></option>
				<?php
				}
				?>
			</select>
		</div>
		<?php
		if ( $this->options->get( 'show_admin_perms' ) ) {?>
			<div class="column">
				<label for="cb-immunity"><?php esc_html_e( 'Immunity', 'crinisbans' )?></label>
				<input type="text" id="cb-immunity" name="cb-immunity" value="<?php echo esc_attr( $attrs['admin']->get_immunity() ) ?>" />
			</div>
			<div class="column">
				<h3><?php esc_html_e( 'Flags','crinisbans' )?></h3>
				<?php
				$current_flags = $attrs['admin']->get_flags();
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
		<?php }?>
		<?php $this->viewhelper->object_list( 'cb-groups','Groups',$attrs['all_groups'],$attrs['admin']->get_group_post_ids() ) ?>
	</div>
</div>
