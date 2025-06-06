<?php if ( is_active_sidebar( 'footer-a' ) || is_active_sidebar( 'footer-b' ) || is_active_sidebar( 'footer-c' ) ) : ?>

	<div class="footer section medium-padding bg-graphite">
	
		<div class="section-inner row">
		
			<?php if ( is_active_sidebar( 'footer-a' ) ) : ?>
			
				<div class="column column-1 one-third">
				
					<div class="widgets">
			
						<?php dynamic_sidebar( 'footer-a' ); ?>
											
					</div>
					
				</div><!-- .column-1 -->
				
			<?php endif; ?>
				
			<?php if ( is_active_sidebar( 'footer-b' ) ) : ?>
			
				<div class="column column-2 one-third">
				
					<div class="widgets">
			
						<?php dynamic_sidebar( 'footer-b' ); ?>
											
					</div><!-- .widgets -->
					
				</div><!-- .column-2 -->
				
			<?php endif; ?>
								
			<?php if ( is_active_sidebar( 'footer-c' ) ) : ?>
			
				<div class="column column-3 one-third">
			
					<div class="widgets">
			
						<?php dynamic_sidebar( 'footer-c' ); ?>
											
					</div><!-- .widgets -->
					
				</div>
				
			<?php endif; ?><!-- .footer-c -->
			
			<div class="clear"></div>
		
		</div><!-- .section-inner -->

	</div><!-- .footer -->

<?php endif; ?>

<div class="credits section bg-dark small-padding">

	<div class="credits-inner section-inner">

		<p class="credits-left fleft">
		
			&copy; <?php echo date( 'Y' ) ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		
		</p>
		
		<p class="credits-right fright">
			
			<a class="tothetop" href="#"><?php _e( 'Up', 'baskerville' ); ?> &uarr;</a>
			
		</p>
		
		<div class="clear"></div>
	
	</div><!-- .credits-inner -->
	
</div><!-- .credits -->

<?php wp_footer(); ?>

</body>
</html>