<?php

	function runWidget($class = '', $title = '', $content = '') { ?>

		<div class="widget <?php echo $class; ?>">
			<?php if($title) echo '<h3>'.$title.'</h3>'; ?>
			<hr>
			<div class="body_content">
				<?php echo $content; ?>
				<div class="clear"></div>
			</div>
		</div><?php
	}

if( have_rows('widgets') ): while ( have_rows('widgets') ) : the_row();

	if( get_row_layout() == 'simple_type' ):
		runWidget('simple', get_sub_field('title'), get_sub_field('content'));


	elseif( get_row_layout() == 'list_type' ):
		$content = '<ol>';
		if(have_rows('list')) { while (have_rows('list')) { the_row();
			$rawDate = get_sub_field('date');
			$content .= '<li>'.get_sub_field('element');
			if(!empty($rawDate)) {
				$niceDate = date('l d', strtotime($rawDate));
				$content .= '<span>'.$niceDate.'</span>';
			}
			$content .= '</li>';
		}}
		$content .= '</ol>';

		runWidget('list', get_sub_field('title'), $content);
		$content = '';


	elseif( get_row_layout() == 'profile_type' ):
		$p_img = get_sub_field('image');
		$p_imgArray = wp_get_attachment_image_src($p_img, 'medium');
		$p_name = get_sub_field('name');
		$p_bio = get_sub_field('bio');
		if($p_img) $content .= '<img src="'.$p_imgArray[0].'" alt="" class="fleft">';
		if($p_name) $content .= '<p><strong>'.$p_name.'</strong></p>';
		if($p_bio) $content .= '<p>'.$p_bio.'</p>';

		runWidget('list', get_sub_field('title'), $content);
		$content = '';


	elseif( get_row_layout() == 'embed_type' ):
		runWidget('embed', get_sub_field('title'), get_sub_field('embed'));



	endif;
endwhile; endif; ?>
