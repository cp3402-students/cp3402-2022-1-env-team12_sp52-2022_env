<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package baizonn
 */

?>

<footer id="colophon" class="site-footer">
	<nav class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$baizonn_description = get_bloginfo( 'description', 'display' );
			if ( $baizonn_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $baizonn_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			</nav><!-- .site-branding -->
	<nav class="social-menu">
	<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);
			?>
	</nav>
	<nav class="social-menu">
	<?php
		wp_nav_menu(
				array(
					'theme_location' => 'social'
				)
			);
	?>
	</nav>
	

	<div class="site-info">
		<a href="<?php echo esc_url(__('https://wordpress.org/', 'baizonn')); ?>">
			<?php
			/* translators: %s: CMS name, i.e. WordPress. */
			printf(esc_html__('Proudly powered by %s', 'baizonn'), 'WordPress');
			?>
		</a>
		<span class="sep"> | </span>
		<a href="<?php echo esc_url( __('https://github.com/cp3402-students/cp3402-2022-1-site-team12_theme', 'baizonn'));?>" target="_blank">baizonn</a>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>