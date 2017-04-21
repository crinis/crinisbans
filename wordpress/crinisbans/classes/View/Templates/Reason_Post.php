<div class="cb">	
    <div class="tile is-ancestor">
        <div class="tile is-parent is-vertical">
            <div class="tile is-child">
                <?php echo esc_html( $attrs['reason']->get_excerpt() ); ?>
			</div>
			<div class="tile is-child">
				<h5 class="is-detail-heading"><?php esc_html_e( 'Ban duration','crinisbans' ) ?></h5>
				<?php echo esc_html( $this->util->convert_seconds_to_duration( $attrs['reason']->get_duration() ) ) ?>
			</div>
		</div>
	</div>
</div>
