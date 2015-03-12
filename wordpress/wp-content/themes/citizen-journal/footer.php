
	<footer id="colophon" role="contentinfo">
		<div id="site-generator">
			<?php echo __('&copy; ', 'citizen-journal') . esc_attr( get_bloginfo( 'name', 'display' ) );  ?>
            <?php if ( is_front_page() && ! is_paged() ) : ?>
            <?php _e('- Powered by ', 'citizen-journal'); ?><a href="<?php echo esc_url( __( 'http://wordpress.org/', 'citizen-journal' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'citizen-journal' ); ?>"><?php _e('Wordpress' ,'citizen-journal'); ?></a>
			<?php _e(' and ', 'citizen-journal'); ?><a href="<?php echo esc_url( __( 'http://wpthemes.co.nz/', 'citizen-journal' ) ); ?>"><?php _e('WPThemes.co.nz', 'citizen-journal'); ?></a>
            <?php endif; ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>