
		</div>
		<footer id="footer" role="contentinfo">
			<div class="social">
				<div class="max_wrap">
					<form class="" action="index.html" method="post">
						<input type="text" name="" value="" placeholder="Suscríbete a nuestro Newsletter">
						<input type="submit" name="" value="Enviar">
					</form>
					<ul class="sm_tray">
						<li><a href="#"><svg class="icon facebook"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-facebook"></use></svg></a></li>
						<li><a href="#"><svg class="icon twitter"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-twitter"></use></svg></a></li>
						<li><a href="#"><svg class="icon instagram"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-instagram"></use></svg></a></li>
						<li><a href="#"><svg class="icon youtube-play"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-youtube-play"></use></svg></a></li>
					</ul>
					<div id="foot_nav">
						<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
					</div>
				</div>
			</div>
			<div class="sitemap">
				<div class="max_wrap">
					<?php wp_nav_menu( array( 'theme_location' => 'legal-menu', 'container_class' => 'links' ) ); ?>
					<div id="copyright">
						<h3>CENTRO DE LAS ARTES</h3>
						<p>Parque Fundidora Av. Fundidora y Adolfo Prieto, <br>
						Col. Obrera, C.P. 64010, Monterrey, Nuevo León. <br>
						T. +52 (81) 2315 1101</p>
						<p class="legal_note">Todos los derechos reservados CONARTE &copy; <?php the_time('Y'); ?></p>
					</div>
					<a href="#" id="logo_nl"><img src="<?php echo get_template_directory_uri(); ?>/img/logo_nuevoleon.png" alt="" width="140"></a>
				</div>
			</div>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
