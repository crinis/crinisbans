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
		<?php $this->viewhelper->object_list( 'cb-groups','Groups',$attrs['all_groups'],$attrs['admin']->get_group_post_ids() ) ?>
	</div>
</div>
