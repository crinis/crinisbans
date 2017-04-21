
<div class="column">
    <h3><?php echo esc_html( $attrs['title'] )?></h3>
	<?php
	foreach ( $attrs['objects'] as $object ) {
	?>
		<label for="<?php echo esc_attr( $attrs['element'] )?>_<?php echo esc_attr( $object->get_post_id() )?>">
			<input <?php checked( in_array( $object->get_post_id(),$attrs['current_post_ids'],true ) ) ?>type="checkbox" id="<?php echo esc_attr( $field )?>_<?php echo esc_attr( $object->get_post_id() )?>" name="<?php echo esc_attr( $element )?>[]" value="<?php echo esc_attr( $object->get_post_id() )?>"/>
			<?php echo esc_html( $object->get_title() )?>
		</label>	
		<br>
	<?php
	}
	?>
</div>
