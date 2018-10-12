<?php
/**
 * The default template part for posts.
 *
 * @package    _s
 * @subpackage Theme
 */

namespace _s\Theme;

?>
<div class="post">
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php
	the_post_thumbnail(
		'medium',
		[
			'class'           => 'lazyload',
			'ratio_container' => true,
		]
	);
	?>
	<?php the_content(); ?>
</div>
