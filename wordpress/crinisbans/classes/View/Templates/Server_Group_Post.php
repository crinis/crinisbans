<div class="cb">
    <div class="tile is-ancestor">
        <div class="tile is-parent is-vertical">
            <div class="tile is-child">
                <?php echo esc_html( $attrs['server_group']->get_excerpt() ); ?>
			</div>
			<div class="tile is-child">
				<h5 class="is-detail-heading"><?php esc_html_e( 'All servers in this group','crinisbans' ) ?></h5>
				<div id="cb-server-group-post"></div>
			</div>
		</div>
	</div>
</div>
