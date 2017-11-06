<?php
/*
Designaholic Templates
Post Comentarios
*/


if ( post_password_required() ) {
	return;
}


if ( comments_open() ) : ?>
<div class="post_comentarios fondo_gris_calido bloque_horizontal">
	<div class="container spacer">
		<h3><?php

			if(have_comments()) {
				printf( _nx( 'Un comentario en “%2$s”', '%1$s comentarios en “%2$s”', get_comments_number(), 'Comentarios'),
				number_format_i18n( get_comments_number() ), get_the_title() );
			} else {
				echo 'Tienes algo que decir de: "'. get_the_title(). '"';
			} ?>
		</h3>


		<div class="row">
			<div class="five columns"><?php

				$comments_args = array(
					'fields' => apply_filters( 'comment_form_default_fields', array(
						'author' =>	'<input id="author" name="author" type="text" value="' .
									esc_attr( $commenter['comment_author'] ) . '" placeholder="' .
									__( 'Tu Nombre' ) . ' ' . ( $req ? ' *' : '' ) . '" size="30" />',
						'email' =>	'<input id="email" name="email" type="email" value="' .
									esc_attr( $commenter['comment_author'] ) . '" placeholder="' .
									__( 'Tu Correo' ) . ' ' . ( $req ? ' *' : '' ) . '" size="30" />',
						'url'    => '' ) ),
						'label_submit' => 'Comentar',
						'title_reply' => '',
						'title_reply_to' => '',
						'title_reply_before' => '',
						'title_reply_after' => '',
						'comment_notes_after' => '',
						'comment_field' => '<textarea id="comment" name="comment" placeholder="' . _x( 'Tus comentarios', 'noun' ) . '" aria-required="true"></textarea>',
					);
				comment_form($comments_args); ?>
			</div>


			<div class="six columns offset-by-one">
				<ul class="comentarios_listado"><?php
					if(have_comments()) {
						wp_list_comments( array(
							'short_ping'  => true,
							'avatar_size' => 50,
						) );
					} else {
						echo 'Sé el primero en comentar.';
					}
					?>
				</ul>
			</div>
		</div>


	</div>
</div><?php

endif; ?>
